import "../css/client.css";
import $ from "jquery";
window.$ = $;
window.jQuery = $;

// Import logic đặt vé nhanh cho trang chủ
if (window.location.pathname === "/" || window.location.pathname === "/home") {
    import("./trang-chu.js");
}

// Logic đặt vé nhanh
function initBookingFast() {
    console.log("Init booking fast from client.js");
    // Load danh sách chi nhánh khi trang được tải
    loadChiNhanhs();

    // Xử lý sự kiện thay đổi dropdown
    setupDropdownEvents();
}

function loadChiNhanhs() {
    console.log("Loading chi nhanhs from client.js");
    showLoading();

    $.ajax({
        url: "/api/chi-nhanhs",
        method: "GET",
        success: function (data) {
            console.log("Chi nhanhs loaded:", data);
            const select = $("#select-chi-nhanh");
            select.empty();
            select.append(
                '<option value="" disabled selected>1-Chọn rạp</option>'
            );

            data.forEach(function (chiNhanh) {
                select.append(
                    `<option value="${chiNhanh.id}">${chiNhanh.ten_chi_nhanh}</option>`
                );
            });

            hideLoading();
        },
    });
}

function setupDropdownEvents() {
    console.log("Setting up dropdown events");
    // Sự kiện chọn chi nhánh
    $("#select-chi-nhanh").on("change", function () {
        console.log("Chi nhanh changed:", $(this).val());
        const chiNhanhId = $(this).val();
        if (chiNhanhId) {
            loadPhimsByChiNhanh(chiNhanhId);
            resetDropdowns(["#select-phim", "#select-date", "#select-suat"]);
            enableDropdown("#select-phim");
        }
    });

    // Sự kiện chọn phim
    $("#select-phim").on("change", function () {
        console.log("Phim changed:", $(this).val());
        const phimId = $(this).val();
        const chiNhanhId = $("#select-chi-nhanh").val();
        if (phimId && chiNhanhId) {
            loadNgayChieuByPhim(phimId, chiNhanhId);
            resetDropdowns(["#select-date", "#select-suat"]);
            enableDropdown("#select-date");
        }
    });

    // Sự kiện chọn ngày
    $("#select-date").on("change", function () {
        console.log("Date changed:", $(this).val());
        const ngayChieu = $(this).val();
        const phimId = $("#select-phim").val();
        const chiNhanhId = $("#select-chi-nhanh").val();
        if (ngayChieu && phimId && chiNhanhId) {
            loadSuatChieuByNgay(phimId, chiNhanhId, ngayChieu);
            resetDropdowns(["#select-suat"]);
            enableDropdown("#select-suat");
        }
    });

    // Sự kiện chọn suất chiếu
    $("#select-suat").on("change", function () {
        console.log("Suat chieu changed:", $(this).val());
        const suatChieuId = $(this).val();
        if (suatChieuId) {
            enableButton("#btn-dat-ngay");
        }
    });

    // Sự kiện click nút đặt ngay
    $("#btn-dat-ngay").on("click", function () {
        const suatChieuId = $("#select-suat").val();
        if (suatChieuId) {
            // Chuyển đến trang đặt vé chi tiết
            window.location.href = `/dat-ve?suat_chieu_id=${suatChieuId}`;
        }
    });

    // Ẩn option đầu tiên khi đã chọn
    $(".movie-select").on("change", function () {
        $(this).find("option:first").hide();
    });
}

function loadPhimsByChiNhanh(chiNhanhId) {
    console.log("Loading phims for chi nhanh:", chiNhanhId);
    showLoading();

    $.ajax({
        url: "/api/phims-by-chi-nhanh",
        method: "GET",
        data: { chi_nhanh_id: chiNhanhId },
        success: function (data) {
            console.log("Phims loaded:", data);
            const select = $("#select-phim");
            select.empty();
            select.append(
                '<option value="" disabled selected>2-Chọn phim</option>'
            );

            data.forEach(function (phim) {
                select.append(
                    `<option value="${phim.id}">${phim.ten_phim}</option>`
                );
            });

            hideLoading();
        },
        error: function () {
            console.error("Error loading phims");
            hideLoading();
            showError("Không thể tải danh sách phim. Vui lòng thử lại!");
        },
    });
}

function loadNgayChieuByPhim(phimId, chiNhanhId) {
    console.log(
        "Loading ngay chieu for phim:",
        phimId,
        "chi nhanh:",
        chiNhanhId
    );
    showLoading();

    $.ajax({
        url: "/api/ngay-chieu-by-phim",
        method: "GET",
        data: {
            phim_id: phimId,
            chi_nhanh_id: chiNhanhId,
        },
        success: function (data) {
            console.log("Ngay chieu loaded:", data);
            const select = $("#select-date");
            select.empty();
            select.append(
                '<option value="" disabled selected>3-Chọn ngày</option>'
            );

            data.forEach(function (ngay) {
                select.append(
                    `<option value="${ngay.ngay_chieu}">${ngay.ngay_hien_thi}</option>`
                );
            });

            hideLoading();
        },
        error: function () {
            console.error("Error loading ngay chieu");
            hideLoading();
            showError("Không thể tải lịch chiếu. Vui lòng thử lại!");
        },
    });
}

function loadSuatChieuByNgay(phimId, chiNhanhId, ngayChieu) {
    console.log("Loading suat chieu for:", phimId, chiNhanhId, ngayChieu);
    showLoading();

    $.ajax({
        url: "/api/suat-chieu-by-ngay",
        method: "GET",
        data: {
            phim_id: phimId,
            chi_nhanh_id: chiNhanhId,
            ngay_chieu: ngayChieu,
        },
        success: function (data) {
            console.log("Suat chieu loaded:", data);
            const select = $("#select-suat");
            select.empty();
            select.append(
                '<option value="" disabled selected>4-Chọn suất</option>'
            );

            data.forEach(function (suat) {
                select.append(
                    `<option value="${suat.id}">${suat.hien_thi}</option>`
                );
            });

            hideLoading();
        },
        error: function () {
            console.error("Error loading suat chieu");
            hideLoading();
            showError("Không thể tải suất chiếu. Vui lòng thử lại!");
        },
    });
}

// Hàm helper
function resetDropdowns(selectors) {
    selectors.forEach(function (selector) {
        const select = $(selector);
        select.empty();

        // Thêm option mặc định dựa trên selector
        if (selector === "#select-phim") {
            select.append(
                '<option value="" disabled selected>2-Chọn phim</option>'
            );
        } else if (selector === "#select-date") {
            select.append(
                '<option value="" disabled selected>3-Chọn ngày</option>'
            );
        } else if (selector === "#select-suat") {
            select.append(
                '<option value="" disabled selected>4-Chọn suất</option>'
            );
        }

        select.prop("disabled", true);
    });

    // Disable nút đặt ngay
    disableButton("#btn-dat-ngay");
}

function enableDropdown(selector) {
    $(selector).prop("disabled", false);
}

function disableDropdown(selector) {
    $(selector).prop("disabled", true);
}

function enableButton(selector) {
    $(selector).prop("disabled", false);
}

function disableButton(selector) {
    $(selector).prop("disabled", true);
}

function showLoading() {
    $("#booking-loading").show();
}

function hideLoading() {
    $("#booking-loading").hide();
}

function showError(message) {
    // Sử dụng SweetAlert2 nếu có, hoặc alert thông thường
    if (typeof Swal !== "undefined") {
        Swal.fire({
            icon: "error",
            title: "Lỗi!",
            text: message,
            confirmButtonText: "OK",
        });
    } else {
        alert(message);
    }
}
$(document).ready(function () {
    console.log("Client JS loaded");

    // Khởi tạo đặt vé nhanh nếu đang ở trang chủ
    if (
        window.location.pathname === "/" ||
        window.location.pathname === "/home"
    ) {
        console.log("Initializing booking fast");
        initBookingFast();
    }

    $(".user-toggle").on("click", function (e) {
        e.stopPropagation();
        $("#userDropdown").toggle();
    });

    $(document).on("click", function (e) {
        if (!$(e.target).closest(".user-toggle, #userDropdown").length) {
            $("#userDropdown").hide();
        }
    });
});

// Khởi tạo Laravel Echo với socket.io (chỉ khi có thư viện Echo và đang ở trang có ghế)
try {
    const hasSeatGrid = document.querySelector(".ghe-chieu") !== null;
    const EchoClass = typeof window.Echo === "function" ? window.Echo : null;
    if (hasSeatGrid && EchoClass) {
        window.Echo = new EchoClass({
            broadcaster: "socket.io",
            host: window.location.hostname + ":6001",
        });
    }
} catch (e) {
    console.warn("Echo init skipped:", e?.message || e);
}

// Khi có người chọn ghế -> tất cả client khác sẽ nhận được sự kiện này
if (
    typeof window.Echo !== "undefined" &&
    window.Echo &&
    typeof window.Echo.channel === "function"
) {
    window.Echo.channel("ghe-duoc-chon").listen(".ghe-duoc-chon", function (e) {
        console.log("Đã nhận được sự kiện ghe-duoc-chon:", e);
        const ghe = document.querySelector(
            `.ghe-chieu[data-seat-id="${e.gheId}"]`
        );
        const currentUserId = parseInt(
            document.querySelector('meta[name="user-id"]').content
        );

        if (ghe && e.userId !== currentUserId) {
            // Kiểm tra xem ghế có phải là ghế đôi
            const isCoupleSeat = ghe.classList.contains("ghe-doi");
            if (isCoupleSeat) {
                const seatName = ghe.getAttribute("data-seat-name");
                const seatNumber = parseInt(seatName.match(/\d+/)[0]);
                const row = seatName.match(/[A-Za-z]+/)[0];
                const partnerSeatNumber =
                    seatNumber % 2 === 1 ? seatNumber + 1 : seatNumber - 1;
                const partnerSeatName = row + partnerSeatNumber;
                const partnerSeat = document.querySelector(
                    `.ghe-chieu[data-seat-name="${partnerSeatName}"]`
                );

                // Cập nhật cả hai ghế
                [ghe, partnerSeat].forEach((seat) => {
                    if (seat) {
                        seat.classList.add("selected-by-other");
                        seat.disabled = true;
                    }
                });
            } else {
                ghe.classList.add("selected-by-other");
                ghe.disabled = true;
            }

            const thongBao = document.getElementById("thong-bao-ghe");
            if (thongBao) {
                thongBao.innerText = `⚠️ Ghế số ${e.gheId} vừa được người khác chọn. Vui lòng chọn ghế khác.`;
                thongBao.style.display = "block";

                setTimeout(() => {
                    thongBao.style.display = "none";
                }, 5000);
            }
        }
    });

    // Khi người dùng hủy chọn ghế
    window.Echo.channel("ghe-bi-huy").listen(".ghe-bi-huy", function (e) {
        const ghe = document.querySelector(
            `.ghe-chieu[data-seat-id="${e.gheId}"]`
        );
        const currentUserId = parseInt(
            document.querySelector('meta[name="user-id"]').content
        );

        if (ghe && e.userId !== currentUserId) {
            const isCoupleSeat = ghe.classList.contains("ghe-doi");
            if (isCoupleSeat) {
                const seatName = ghe.getAttribute("data-seat-name");
                const seatNumber = parseInt(seatName.match(/\d+/)[0]);
                const row = seatName.match(/[A-Za-z]+/)[0];
                const partnerSeatNumber =
                    seatNumber % 2 === 1 ? seatNumber + 1 : seatNumber - 1;
                const partnerSeatName = row + partnerSeatNumber;
                const partnerSeat = document.querySelector(
                    `.ghe-chieu[data-seat-name="${partnerSeatName}"]`
                );

                // Cập nhật cả hai ghế
                [ghe, partnerSeat].forEach((seat) => {
                    if (seat) {
                        seat.classList.remove("selected-by-other");
                        seat.disabled = false;
                    }
                });
            } else {
                ghe.classList.remove("selected-by-other");
                ghe.disabled = false;
            }
        }
    });
}

// Khi tải lại trang, vô hiệu hóa các ghế đã bị chọn bởi người khác
document.querySelectorAll(".ghe-chieu.selected-by-other").forEach((ghe) => {
    ghe.disabled = true;
});
// Gắn sự kiện click vào từng ghế
document.querySelectorAll(".ghe-chieu").forEach((ghe) => {
    ghe.addEventListener("click", function () {
        const gheId = this.getAttribute("data-seat-id");
        const gheElement = this;

        if (gheElement.classList.contains("selected-by-other")) {
            alert("Ghế này đã được người khác chọn!");
            gheElement.classList.add("selected-by-other");
            gheElement.disabled = true;
            return;
        }

        fetch("/chon-ghe", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                ghe_id: gheId,
            }),
        })
            .then((response) => {
                if (!response.ok) {
                    if (response.status === 409) {
                        return response.json().then((data) => {
                            alert(data.message);
                            // Xử lý ghế đôi
                            if (gheElement.classList.contains("ghe-doi")) {
                                const seatName =
                                    gheElement.getAttribute("data-seat-name");
                                const seatNumber = parseInt(
                                    seatName.match(/\d+/)[0]
                                );
                                const row = seatName.match(/[A-Za-z]+/)[0];
                                const partnerSeatNumber =
                                    seatNumber % 2 === 1
                                        ? seatNumber + 1
                                        : seatNumber - 1;
                                const partnerSeatName = row + partnerSeatNumber;
                                const partnerSeat = document.querySelector(
                                    `.ghe-chieu[data-seat-name="${partnerSeatName}"]`
                                );

                                // Cập nhật cả hai ghế
                                [gheElement, partnerSeat].forEach((seat) => {
                                    if (seat) {
                                        seat.classList.remove(
                                            "selected",
                                            "selected-by-me"
                                        );
                                        seat.classList.add("selected-by-other");
                                        seat.disabled = true;
                                    }
                                });
                            } else {
                                gheElement.classList.remove(
                                    "selected",
                                    "selected-by-me"
                                );
                                gheElement.classList.add("selected-by-other");
                                gheElement.disabled = true;
                            }
                        });
                    } else {
                        throw new Error("Đã xảy ra lỗi không xác định");
                    }
                }
                return response.json();
            })
            .then((data) => {
                if (data && data.success) {
                    console.log(" Đã chọn ghế thành công!");
                    gheElement.classList.add("selected-by-me");
                    gheElement.disabled = false;
                }
            })
            .catch((error) => {
                console.error("Lỗi khi chọn ghế:", error);
            });
    });
});

let countdownTimer;

function startTimer(duration, display) {
    if (countdownTimer) {
        clearInterval(countdownTimer);
    }

    let timer = duration;
    let startTime = Date.now();
    let endTime = startTime + timer * 1000;

    // Lưu thời gian kết thúc vào localStorage
    localStorage.setItem("timerEndTime", endTime);

    function updateTimer() {
        let currentTime = Date.now();
        let remainingTime = Math.ceil((endTime - currentTime) / 1000);

        if (remainingTime <= 0) {
            clearInterval(countdownTimer);
            handleTimeout();
            return;
        }

        let minutes = parseInt(remainingTime / 60, 10);
        let seconds = parseInt(remainingTime % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;
    }

    updateTimer();
    countdownTimer = setInterval(updateTimer, 1000);
}

function handleTimeout() {
    // Clear localStorage
    localStorage.removeItem("timerEndTime");

    // Bỏ chọn hết ghế
    const selectedSeatsList = document.getElementById("selected-seats-list");
    if (selectedSeatsList) {
        selectedSeatsList.textContent = "Chưa chọn ghế";
    }

    // Disable nút đặt vé
    const btnDatVe = document.getElementById("btn-dat-ve");
    if (btnDatVe) {
        btnDatVe.disabled = true;
    }

    // Hiển thị thông báo
    alert("Đã hết thời gian giữ ghế!");

    // Chuyển về trang home
    window.location.href = "/";
}

function clearTimer() {
    if (countdownTimer) {
        clearInterval(countdownTimer);
    }
    localStorage.removeItem("timerEndTime");

    const timeDisplay = document.querySelector(".time-seat");
    if (timeDisplay) {
        timeDisplay.textContent = "05:00";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const selectedSeatsList = document.getElementById("selected-seats-list");
    const timeDisplay = document.querySelector(".time-seat");
    const btnDatVe = document.getElementById("btn-dat-ve");

    // Thêm event listener cho nút đặt vé
    if (btnDatVe) {
        btnDatVe.addEventListener("click", function () {
            clearTimer(); // Xóa timer khi bấm nút đặt vé
        });
    }

    // Kiểm tra xem có timer đang chạy từ trước không
    const savedEndTime = localStorage.getItem("timerEndTime");
    if (savedEndTime) {
        const currentTime = Date.now();
        const remainingTime = Math.ceil((savedEndTime - currentTime) / 1000);

        if (remainingTime > 0) {
            startTimer(remainingTime, timeDisplay);
        } else {
            handleTimeout();
        }
    }

    // Observer để theo dõi thay đổi trong selected-seats-list
    const selectedSeatsObserver = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (
                selectedSeatsList &&
                timeDisplay &&
                selectedSeatsList.textContent !== "Chưa chọn ghế" &&
                !localStorage.getItem("timerEndTime")
            ) {
                startTimer(5 * 60, timeDisplay);
            }
        });
    });

    if (selectedSeatsList) {
        selectedSeatsObserver.observe(selectedSeatsList, {
            childList: true,
            characterData: true,
            subtree: true,
        });
    }
});

// Xử lý khi rời trang
window.addEventListener("beforeunload", function () {
    const timeDisplay = document.querySelector(".time-seat");
    if (
        timeDisplay &&
        timeDisplay.textContent !== "05:00" &&
        timeDisplay.textContent !== "Hết giờ!"
    ) {
        // Timer đang chạy, đã được lưu trong localStorage
    }
});
