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
function setupBookingButton() {
    $("#btn-dat-ve").on("click", function () {
        if ($(this).prop("disabled")) return;

        const selectedSeats = getSelectedSeats();
        if (selectedSeats.length === 0) {
            showError("Vui lòng chọn ít nhất một ghế!");
            return;
        }

        // Hiển thị loading
        $(this).prop("disabled", true).text("Đang xử lý...");

        // Chuẩn bị dữ liệu
        const formData = prepareBookingData();

        // Gửi request
        console.log("Sending booking request...", formData);
        $.ajax({
            url: "/dat-ve",
            method: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                console.log("Booking response:", response);
                if (response.success) {
                    showSuccess("Đặt vé thành công!");
                    // Chuyển đến trang kết quả đặt vé client
                    setTimeout(() => {
                        console.log(
                            "Redirecting to:",
                            `/dat-ve/ket-qua/${response.dat_ve_id}`
                        );
                        window.location.href = `/dat-ve/ket-qua/${response.dat_ve_id}`;
                    }, 1500);
                } else {
                    showError(response.message || "Có lỗi xảy ra!");
                    resetBookingButton();
                }
            },
            error: function (xhr) {
                console.error("Booking error:", xhr);
                const response = xhr.responseJSON;
                showError(response?.message || "Có lỗi xảy ra khi đặt vé!");
                resetBookingButton();
            },
        });
    });
}

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
