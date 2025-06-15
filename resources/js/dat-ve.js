// 1. Import jQuery đầu tiên nếu các script sau cần dùng $
import $ from "jquery";
window.$ = $;
window.jQuery = $;

// 2. Import Bootstrap setup (nếu bootstrap dùng jQuery thì jQuery phải có trước)
import "./bootstrap";

// 4. Import custom CSS (tuỳ bạn có thể để đầu hoặc cuối)
import "../css/dat-ve.css";

let scanned = false;

function startScanner() {
    const $scannerEl = $("#barcode-scanner");

    if ($scannerEl.length === 0) {
        console.warn("Không tìm thấy phần tử barcode-scanner.");
        return;
    }

    Quagga.init(
        {
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: $scannerEl[0],
                constraints: {
                    facingMode: "environment",
                },
            },
            decoder: {
                readers: ["code_128_reader"],
            },
        },
        function (err) {
            if (err) {
                console.error("Lỗi Quagga:", err);
                alert("Không thể bật camera: " + err.message);
                return;
            }
            Quagga.start();
        }
    );

    Quagga.offDetected();
    Quagga.onDetected(onScan);
}

function stopScanner() {
    if (Quagga) {
        Quagga.stop();
        Quagga.offDetected();
    }
    scanned = false;
    $("#scan-result").text("Chưa quét");
}

function onScan(data) {
    if (scanned) return;
    scanned = true;

    const code = data.codeResult.code;
    $("#scan-result").text(code);

    $.ajax({
        url: "/checkin-ve",
        method: "POST",
        contentType: "application/json",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
        data: JSON.stringify({
            ma_ve: code,
        }),
        success: function (res) {
            alert(res.message);
        },
        error: function () {
            alert("Mã không hợp lệ hoặc đã check-in!");
        },
        complete: function () {
            setTimeout(() => (scanned = false), 2000);
        },
    });
}

$(document).ready(function () {
    // Khi modal mở
    $("#scannerModal").on("shown.bs.modal", function () {
        setTimeout(startScanner, 400);
    });

    // Khi modal đóng
    $("#scannerModal").on("hidden.bs.modal", function () {
        stopScanner();
    });

    // Nút Quét lại
    $("#restartScan").on("click", function () {
        stopScanner();
        setTimeout(startScanner, 400);
    });
});
