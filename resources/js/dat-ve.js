import $ from "jquery";
window.$ = $;
window.jQuery = $;

import "./bootstrap";
// import "../css/dat-ve.css";

import Quagga from "@ericblade/quagga2";

let scanned = false;

function startScanner() {
    const $scannerEl = $('#barcode-scanner');

    if ($scannerEl.length === 0) {
        console.warn("Không tìm thấy phần tử #barcode-scanner.");
        return;
    }

    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: $scannerEl[0],
            constraints: {
                facingMode: "environment"
            }
        },
        decoder: {
            readers: ["code_128_reader"]
        }
    }, function (err) {
        if (err) {
            console.error("❌ Lỗi khởi tạo Quagga:", err);
            return;
        }
        Quagga.start();
    });

    Quagga.offDetected(); // reset
    Quagga.onDetected(onScan);
}

function stopScanner() {
    if (Quagga) {
        Quagga.stop();
        Quagga.offDetected();
    }
    scanned = false;
    $('#scan-result').text('Chưa quét');
}

function onScan(data) {
    if (scanned) return;
    scanned = true;

    const code = data.codeResult.code;
    $('#scan-result').text(code);

    window.location.href = `/admin/dat-ve?ma_ve=${code}`;
}


$(document).ready(function () {
    $('#scannerModal').on('shown.bs.modal', function () {
        setTimeout(startScanner, 500);
    });

    $('#scannerModal').on('hidden.bs.modal', function () {
        stopScanner();
    });

    $('#restartScan').on('click', function () {
        stopScanner();
        setTimeout(startScanner, 500);
    });
});
