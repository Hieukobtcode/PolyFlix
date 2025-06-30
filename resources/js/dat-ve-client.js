import $ from "jquery";
window.$ = $;
window.jQuery = $;
import "../css/dat-ve.css";

$(document).ready(function () {
    console.log("dat-ve-client.js loaded successfully");
    initDatVe();
});

function initDatVe() {
    // Khởi tạo các sự kiện
    setupSeatSelection();
    setupFoodTabs();
    setupQuantityControls();
    setupBookingButton();

    // Cập nhật tóm tắt ban đầu
    updateOrderSummary();
}

// Xử lý chọn ghế
function setupSeatSelection() {
    $(".seat.available").on("click", function () {
        $(this).toggleClass("selected");
        updateOrderSummary();
    });
}

// Xử lý tab đồ ăn
function setupFoodTabs() {
    $(".tab-btn").on("click", function () {
        const tabId = $(this).data("tab");

        // Cập nhật active tab
        $(".tab-btn").removeClass("active");
        $(this).addClass("active");

        // Hiển thị nội dung tab
        $(".tab-content").removeClass("active");
        $("#" + tabId).addClass("active");
    });
}

// Xử lý tăng giảm số lượng
function setupQuantityControls() {
    $(".qty-btn").on("click", function () {
        const target = $(this).data("target");
        const input = $("#" + target);
        const isPlus = $(this).hasClass("plus");
        const currentValue = parseInt(input.val()) || 0;

        if (isPlus) {
            const maxValue = parseInt(input.attr("max")) || 10;
            if (currentValue < maxValue) {
                input.val(currentValue + 1);
            }
        } else {
            const minValue = parseInt(input.attr("min")) || 0;
            if (currentValue > minValue) {
                input.val(currentValue - 1);
            }
        }

        updateOrderSummary();
    });
}

// Xử lý nút đặt vé
// function setupBookingButton() {
//     $("#btn-dat-ve").on("click", function () {
//         if ($(this).prop("disabled")) return;

//         const selectedSeats = getSelectedSeats();
//         if (selectedSeats.length === 0) {
//             showError("Vui lòng chọn ít nhất một ghế!");
//             return;
//         }

//         // Hiển thị loading
//         $(this).prop("disabled", true).text("Đang xử lý...");

//         // Chuẩn bị dữ liệu
//         const formData = prepareBookingData();

//         // Gửi request
//         console.log("Sending booking request...", formData);
//         $.ajax({
//             url: "/dat-ve",
//             method: "POST",
//             data: formData,
//             headers: {
//                 "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//             },
//             success: function (response) {
//                 console.log("Booking response:", response);
//                 if (response.success) {
//                     showSuccess("Đặt vé thành công!");
//                     // Chuyển đến trang kết quả đặt vé client
//                     setTimeout(() => {
//                         console.log(
//                             "Redirecting to:",
//                             `/dat-ve/ket-qua/${response.dat_ve_id}`
//                         );
//                         window.location.href = `/dat-ve/ket-qua/${response.dat_ve_id}`;
//                     }, 1500);
//                 } else {
//                     showError(response.message || "Có lỗi xảy ra!");
//                     resetBookingButton();
//                 }
//             },
//             error: function (xhr) {
//                 console.error("Booking error:", xhr);
//                 const response = xhr.responseJSON;
//                 showError(response?.message || "Có lỗi xảy ra khi đặt vé!");
//                 resetBookingButton();
//             },
//         });
//     });
// }

// Lấy danh sách ghế đã chọn
function getSelectedSeats() {
    const seats = [];
    $(".seat.selected").each(function () {
        seats.push({
            id: $(this).data("seat-id"),
            name: $(this).data("seat-name"),
            price: $(this).data("seat-price"),
        });
    });
    return seats;
}

// Lấy danh sách đồ ăn đã chọn
function getSelectedFood() {
    const food = [];
    $('input[name^="do_an"]').each(function () {
        const quantity = parseInt($(this).val()) || 0;
        if (quantity > 0) {
            const foodItem = $(this).closest(".food-item");
            food.push({
                id: $(this).attr("name").match(/\d+/)[0],
                name: foodItem.find("h4").text(),
                price: parseInt(
                    foodItem.find(".price").text().replace(/\D/g, "")
                ),
                quantity: quantity,
            });
        }
    });
    return food;
}

// Lấy danh sách combo đã chọn
function getSelectedCombos() {
    const combos = [];
    $('input[name^="combo"]').each(function () {
        const quantity = parseInt($(this).val()) || 0;
        if (quantity > 0) {
            const comboItem = $(this).closest(".food-item");
            combos.push({
                id: $(this).attr("name").match(/\d+/)[0],
                name: comboItem.find("h4").text(),
                price: parseInt(
                    comboItem.find(".price").text().replace(/\D/g, "")
                ),
                quantity: quantity,
            });
        }
    });
    return combos;
}

// Chuẩn bị dữ liệu đặt vé
function prepareBookingData() {
    const selectedSeats = getSelectedSeats();
    const selectedFood = getSelectedFood();
    const selectedCombos = getSelectedCombos();

    const formData = new FormData();
    formData.append("suat_chieu_id", $('input[name="suat_chieu_id"]').val());

    // Thêm ghế
    selectedSeats.forEach((seat) => {
        formData.append("ghe_ids[]", seat.id);
    });

    // Thêm đồ ăn
    selectedFood.forEach((food) => {
        formData.append(`do_an[${food.id}]`, food.quantity);
    });

    // Thêm combo
    selectedCombos.forEach((combo) => {
        formData.append(`combo[${combo.id}]`, combo.quantity);
    });

    return formData;
}

// Cập nhật tóm tắt đơn hàng
function updateOrderSummary() {
    const selectedSeats = getSelectedSeats();
    const selectedFood = getSelectedFood();
    const selectedCombos = getSelectedCombos();

    // Cập nhật danh sách ghế
    updateSelectedSeats(selectedSeats);

    // Cập nhật danh sách đồ ăn
    updateSelectedFood(selectedFood, selectedCombos);

    // Tính tổng tiền
    const totalAmount = calculateTotal(
        selectedSeats,
        selectedFood,
        selectedCombos
    );
    $("#total-amount").text(formatCurrency(totalAmount));

    // Enable/disable nút đặt vé
    const hasSeats = selectedSeats.length > 0;
    $("#btn-dat-ve").prop("disabled", !hasSeats);
}

// Cập nhật danh sách ghế đã chọn
function updateSelectedSeats(seats) {
    const container = $("#selected-seats-list");
    if (seats.length === 0) {
        container.text("Chưa chọn ghế");
    } else {
        const seatNames = seats.map((seat) => seat.name).join(", ");
        const totalPrice = seats.reduce((sum, seat) => sum + seat.price, 0);
        container.html(`
            <div>${seatNames}</div>
            <div class="text-muted">${formatCurrency(totalPrice)}</div>
        `);
    }
}

// Cập nhật danh sách đồ ăn đã chọn
function updateSelectedFood(food, combos) {
    const container = $("#selected-food-list");
    const allItems = [...food, ...combos];

    if (allItems.length === 0) {
        container.text("Chưa chọn");
    } else {
        let html = "";
        allItems.forEach((item) => {
            const totalPrice = item.price * item.quantity;
            html += `
                <div class="d-flex justify-content-between">
                    <span>${item.name} x${item.quantity}</span>
                    <span>${formatCurrency(totalPrice)}</span>
                </div>
            `;
        });
        container.html(html);
    }
}

// Tính tổng tiền
function calculateTotal(seats, food, combos) {
    let total = 0;

    // Tính tiền ghế
    total += seats.reduce((sum, seat) => sum + seat.price, 0);

    // Tính tiền đồ ăn
    total += food.reduce((sum, item) => sum + item.price * item.quantity, 0);

    // Tính tiền combo
    total += combos.reduce((sum, item) => sum + item.price * item.quantity, 0);

    return total;
}

// Format tiền tệ
function formatCurrency(amount) {
    return new Intl.NumberFormat("vi-VN").format(amount) + "đ";
}

// Reset nút đặt vé
function resetBookingButton() {
    $("#btn-dat-ve").prop("disabled", false).text("Đặt vé");
}

// Hiển thị thông báo lỗi
function showError(message) {
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

// Hiển thị thông báo thành công
function showSuccess(message) {
    if (typeof Swal !== "undefined") {
        Swal.fire({
            icon: "success",
            title: "Thành công!",
            text: message,
            confirmButtonText: "OK",
        });
    } else {
        alert(message);
    }
}

// Xử lý đặt ghế, validate , lưu tổng tiền
$(document).ready(function () {
    let selectedSeatTypeId = null; // Lưu ID loại ghế đang chọn

    // Hàm cập nhật danh sách ghế và tổng tiền
    function updateSummary() {
        const selectedSeats = $(".ghe-chieu.selected");
        const seatList = $("#selected-seats-list");
        const totalAmount = $("#total-amount");
        const btnDatVe = $("#btn-dat-ve");

        if (selectedSeats.length === 0) {
            seatList.text("Chưa chọn ghế");
            totalAmount.text("0đ");
            btnDatVe.prop("disabled", true);
            selectedSeatTypeId = null; // Đặt lại khi không còn ghế nào
            return;
        }

        let total = 0;
        let seatsByType = {}; // Đối tượng để nhóm ghế theo loại

        selectedSeats.each(function () {
            const seatName = $(this).data("seat-name");
            const tenLoaiGhe = $(this).data("ten-loai-ghe") || "Thường";
            const phuThuLoaiPhong =
                parseFloat($(this).data("phu-thu-loai-phong")) || 0;
            const phuThuLoaiGhe =
                parseFloat($(this).data("phu-thu-loai-ghe")) || 0;
            const phuThuRapPhim =
                parseFloat($(this).data("phu-thu-rap-phim")) || 0;
            total += phuThuLoaiPhong + phuThuLoaiGhe + phuThuRapPhim;

            // Nhóm ghế theo loại
            if (!seatsByType[tenLoaiGhe]) {
                seatsByType[tenLoaiGhe] = [];
            }
            seatsByType[tenLoaiGhe].push(seatName);

            // Debug giá trị
            console.log(
                `Seat: ${seatName}, LoaiPhong: ${phuThuLoaiPhong}, LoaiGhe: ${phuThuLoaiGhe}, RapPhim: ${phuThuRapPhim}`
            );
        });

        // Tạo chuỗi hiển thị theo định dạng "Tên loại ghế: G9, G10"
        let seatDetails = [];
        for (let type in seatsByType) {
            seatDetails.push(`${type}: ${seatsByType[type].join(", ")}`);
        }
        seatList.text(seatDetails.join("; "));

        totalAmount.text(`${total.toLocaleString("vi-VN")}đ`);
        btnDatVe.prop("disabled", false);
    }

    // Hàm lấy số thứ tự của ghế từ tên ghế (ví dụ: A1 -> 1, A10 -> 10)
    function getSeatNumber(seatName) {
        return parseInt(seatName.match(/\d+/)[0]);
    }

    // Hàm lấy tên ghế kề bên trong cùng hàng
    function getAdjacentSeatNames(seatName, rowSeats) {
        const row = seatName.match(/[A-Za-z]+/)[0];
        const number = getSeatNumber(seatName);
        const adjacentNames = [];

        // Lấy ghế kề bên trái và phải
        if (rowSeats.includes(`${row}${number - 1}`))
            adjacentNames.push(`${row}${number - 1}`);
        if (rowSeats.includes(`${row}${number + 1}`))
            adjacentNames.push(`${row}${number + 1}`);

        return adjacentNames;
    }

    // Hàm kiểm tra quy tắc chọn ghế
    function validateSeatSelection() {
        const selectedSeats = $(".ghe-chieu.selected");
        if (selectedSeats.length === 0) return true; // Không cần kiểm tra nếu chưa chọn ghế

        let isValid = true;
        const allSeats = $(".ghe-chieu")
            .map(function () {
                return $(this).data("seat-name");
            })
            .get();

        // Nhóm ghế đã chọn theo hàng
        const selectedByRow = {};
        selectedSeats.each(function () {
            const seatName = $(this).data("seat-name");
            const row = seatName.match(/[A-Za-z]+/)[0];
            if (!selectedByRow[row]) selectedByRow[row] = [];
            selectedByRow[row].push(seatName);
        });

        // Kiểm tra từng hàng
        for (let row in selectedByRow) {
            const rowSeats = allSeats
                .filter((name) => name.startsWith(row))
                .sort((a, b) => getSeatNumber(a) - getSeatNumber(b));
            const minSeat = rowSeats[0];
            const maxSeat = rowSeats[rowSeats.length - 1];
            const selectedInRow = selectedByRow[row].sort(
                (a, b) => getSeatNumber(a) - getSeatNumber(b)
            );
            const selectedNumbers = selectedInRow.map((seat) =>
                getSeatNumber(seat)
            );

            // Kiểm tra tính liên tục
            for (let i = 0; i < selectedNumbers.length - 1; i++) {
                if (selectedNumbers[i + 1] - selectedNumbers[i] > 1) {
                    isValid = false;
                    break;
                }
            }

            // Kiểm tra ghế ngoài cùng
            rowSeats.forEach((seatName) => {
                const adjacentNames = getAdjacentSeatNames(seatName, rowSeats);
                const adjacentElements = adjacentNames.map((name) =>
                    $(`.ghe-chieu[data-seat-name="${name}"]`)
                );
                const isEdgeSeat = seatName === minSeat || seatName === maxSeat;
                const isAdjacentSelected = adjacentElements.some(
                    (el) => el.length && el.hasClass("selected")
                );

                if (
                    isEdgeSeat &&
                    isAdjacentSelected &&
                    !$(`.ghe-chieu[data-seat-name="${seatName}"]`).hasClass(
                        "selected"
                    )
                ) {
                    isValid = false;
                }
            });
        }

        return isValid;
    }

    // Lắng nghe sự kiện click trên các ghế
    $(".ghe-chieu").on("click", function () {
        if (
            $(this).hasClass("occupied") ||
            $(this).hasClass("maintenance") ||
            $(this).prop("disabled")
        ) {
            return;
        }

        const seatName = $(this).data("seat-name");
        const seatTypeId = $(this).data("seat-type-id");
        const isCoupleSeat = $(this).hasClass("ghe-doi");
        const selectedSeatsCount = $(".ghe-chieu.selected").length;
        const maxSeats = 10;

        if (isCoupleSeat) {
            const seatNumber = getSeatNumber(seatName);
            const row = seatName.match(/[A-Za-z]+/)[0];
            const partnerSeatNumber =
                seatNumber % 2 === 1 ? seatNumber + 1 : seatNumber - 1;
            const partnerSeatName = row + partnerSeatNumber;
            const partnerSeat = $(
                `.ghe-chieu[data-seat-name="${partnerSeatName}"]`
            );

            if (
                partnerSeat.hasClass("occupied") ||
                partnerSeat.hasClass("maintenance") ||
                partnerSeat.prop("disabled")
            ) {
                alert(
                    "Cặp ghế này không thể chọn vì một trong hai ghế đã được đặt hoặc bảo trì!"
                );
                return;
            }

            if (
                selectedSeatTypeId &&
                selectedSeatTypeId !== seatTypeId &&
                $(".ghe-chieu.selected").length > 0
            ) {
                alert("Bạn chỉ có thể chọn ghế cùng loại!");
                return;
            }

            if (
                !$(this).hasClass("selected") &&
                selectedSeatsCount + 2 > maxSeats
            ) {
                alert("Bạn không thể chọn quá 10 ghế!");
                return;
            }

            selectedSeatTypeId = seatTypeId;
            const isSelected = $(this).hasClass("selected");
            if (isSelected) {
                $(this).removeClass("selected");
                partnerSeat.removeClass("selected");
            } else {
                $(this).addClass("selected");
                partnerSeat.addClass("selected");
            }
        } else {
            if (
                selectedSeatTypeId &&
                selectedSeatTypeId !== seatTypeId &&
                $(".ghe-chieu.selected").length > 0
            ) {
                alert("Bạn chỉ có thể chọn ghế cùng loại!");
                return;
            }

            if (
                !$(this).hasClass("selected") &&
                selectedSeatsCount + 1 > maxSeats
            ) {
                alert("Bạn không thể chọn quá 10 ghế!");
                return;
            }

            selectedSeatTypeId = seatTypeId;
            $(this).toggleClass("selected");
        }

        updateSummary();
    });

    // Lắng nghe sự kiện click trên nút Next
    $("#btn-dat-ve").on("click", function () {
        if (!validateSeatSelection()) {
            alert(
                "Lỗi: Không được chừa ghế ngoài cùng hoặc bỏ qua ghế giữa các ghế đã chọn!"
            );
            return false;
        }
        // Thêm logic submit form nếu cần
        $("#booking-form").submit();
    });

    // Hàm để thêm class 'ghe-doi' cho ghế đôi khi load trang
    function markCoupleSeats() {
        $(".ghe-chieu").each(function () {
            if ($(this).hasClass("ghe-doi")) {
                $(this).addClass("ghe-doi");
            }
        });
    }

    markCoupleSeats();
});
