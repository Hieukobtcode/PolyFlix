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
        error: function () {
            console.error("Error loading chi nhanhs");
            hideLoading();
            showError("Không thể tải danh sách rạp. Vui lòng thử lại!");
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
