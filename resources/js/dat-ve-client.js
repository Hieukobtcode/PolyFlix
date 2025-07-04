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
    // setupBookingButton();

    // Cập nhật tóm tắt ban đầu
    updateOrderSummary();
}

// Xử lý chọn ghế
function setupSeatSelection() {
    // Không cần handler đơn giản ở đây vì đã có handler phức tạp bên dưới
    console.log("Seat selection setup completed");
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

// Lấy danh sách ghế đã chọn
function getSelectedSeats() {
    const seats = [];
    console.log("=== DEBUG TÍNH GIÁ GHẾ ===");

    $(".ghe-chieu.selected").each(function () {
        // Lấy các phụ thu từ data attributes
        const phuThuLoaiPhong =
            parseFloat($(this).data("phu-thu-loai-phong")) || 0;
        const phuThuLoaiGhe = parseFloat($(this).data("phu-thu-loai-ghe")) || 0;
        const phuThuRapPhim = parseFloat($(this).data("phu-thu-rap-phim")) || 0;

        // Giá vé cơ bản (nếu có trong data, nếu không thì 50000đ mặc định)
        const giaVeCoBan = parseFloat($(this).data("gia-ve-co-ban")) || 0;

        // Tính giá cho 1 ghế = giá cơ bản + phụ thu loại phòng + phụ thu loại ghế
        // (Phụ thu rạp sẽ được cộng 1 lần cho tất cả ở cuối)
        const totalPrice = giaVeCoBan + phuThuLoaiPhong + phuThuLoaiGhe;

        const seatName = $(this).data("seat-name");
        console.log(`Ghế ${seatName}:`);
        console.log(`  - Giá cơ bản: ${giaVeCoBan}`);
        console.log(`  - Phụ thu loại phòng: ${phuThuLoaiPhong}`);
        console.log(`  - Phụ thu loại ghế: ${phuThuLoaiGhe}`);
        console.log(`  - Phụ thu rạp: ${phuThuRapPhim}`);
        console.log(`  - Giá ghế (không bao gồm phụ thu rạp): ${totalPrice}`);

        seats.push({
            id: $(this).data("seat-id"),
            name: seatName,
            type: $(this).data("ten-loai-ghe") || "Thường",
            price: totalPrice,
            rapSurcharge: phuThuRapPhim, // Lưu riêng để cộng 1 lần sau
        });
    });

    console.log("=== KẾT THÚC DEBUG GHẾ ===");
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
        // Nhóm ghế theo loại
        const seatsByType = {};
        seats.forEach((seat) => {
            if (!seatsByType[seat.type]) {
                seatsByType[seat.type] = [];
            }
            seatsByType[seat.type].push(seat.name);
        });

        // Tạo chuỗi hiển thị theo định dạng "Tên loại ghế: G9, G10"
        const seatDetails = [];
        for (let type in seatsByType) {
            seatDetails.push(`${type}: ${seatsByType[type].join(", ")}`);
        }

        const totalPrice = seats.reduce((sum, seat) => sum + seat.price, 0);
        container.html(`
            <div>${seatDetails.join("; ")}</div>
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

    console.log("=== TÍNH TỔNG TIỀN DEBUG ===");

    // Tính tiền ghế (đã bao gồm giá cơ bản + phụ thu loại phòng + phụ thu loại ghế)
    const seatTotal = seats.reduce((sum, seat) => sum + seat.price, 0);
    total += seatTotal;
    console.log("Tiền ghế (không bao gồm phụ thu rạp):", seatTotal);

    // Cộng phụ thu rạp CHỈ 1 LẦN (không phải cho từng ghế)
    if (seats.length > 0) {
        const rapSurcharge = seats[0].rapSurcharge || 0; // Lấy từ ghế đầu tiên
        total += rapSurcharge;
        console.log("Phụ thu rạp (1 lần):", rapSurcharge);
    }

    console.log("Tổng tiền ghế (bao gồm phụ thu rạp):", total);

    // Tính tiền đồ ăn
    const foodTotal = food.reduce(
        (sum, item) => sum + item.price * item.quantity,
        0
    );
    total += foodTotal;
    console.log("Tiền đồ ăn:", foodTotal);

    // Tính tiền combo
    const comboTotal = combos.reduce(
        (sum, item) => sum + item.price * item.quantity,
        0
    );
    total += comboTotal;
    console.log("Tiền combo:", comboTotal);

    console.log("TỔNG CUỐI CÙNG:", total);
    console.log("=== KẾT THÚC DEBUG ===");

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
            // console.log(
            //     `Seat: ${seatName}, LoaiPhong: ${phuThuLoaiPhong}, LoaiGhe: ${phuThuLoaiGhe}, RapPhim: ${phuThuRapPhim}`
            // );
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
        const selectedSeatsCount = $(".ghe-chieu.selected").length;
        const maxSeats = 10;
        const seatId = $(this).data("seat-id");
        const isCoupleSeat = $(this).hasClass("ghe-doi");

        // Hàm hủy ghế
        const cancelSeat = (seatElement, seatId) => {
            seatElement.removeClass("selected");
            $.ajax({
                url: "/huy-chon-ghe",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: {
                    ghe_id: seatId,
                },
                success: () => {
                    console.log(`Ghế ${seatId} đã được hủy chọn`);
                },
                error: (error) => {
                    console.error(`Lỗi khi hủy ghế ${seatId}:`, error);
                },
            });
        };

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

            // Kiểm tra loại ghế
            if (
                selectedSeatTypeId &&
                selectedSeatTypeId !== seatTypeId &&
                $(".ghe-chieu.selected").length > 0
            ) {
                alert("Bạn chỉ có thể chọn ghế cùng loại!");
                // Hủy chọn ghế hiện tại và ghế đôi
                cancelSeat($(this), seatId);
                cancelSeat(partnerSeat, partnerSeat.data("seat-id"));
                return;
            }

            // Kiểm tra số lượng ghế
            if (
                !$(this).hasClass("selected") &&
                selectedSeatsCount + 2 > maxSeats
            ) {
                alert("Bạn không thể chọn quá 10 ghế!");
                // Hủy chọn ghế hiện tại và ghế đôi
                cancelSeat($(this), seatId);
                cancelSeat(partnerSeat, partnerSeat.data("seat-id"));
                return;
            }

            selectedSeatTypeId = seatTypeId;
            const isSelected = $(this).hasClass("selected");
            if (isSelected) {
                // Hủy chọn cặp ghế
                cancelSeat($(this), seatId);
                cancelSeat(partnerSeat, partnerSeat.data("seat-id"));
            } else {
                // Chọn cặp ghế
                $(this).addClass("selected");
                partnerSeat.addClass("selected");

                [$(this), partnerSeat].forEach((el) => {
                    const seatId = el.data("seat-id");
                    $.ajax({
                        url: "/chon-ghe",
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data: {
                            ghe_id: seatId,
                        },
                        success: () => {
                            console.log(`Ghế ${seatId} đã được chọn`);
                        },
                        error: (error) => {
                            console.error(`Lỗi khi chọn ghế ${seatId}:`, error);
                        },
                    });
                });
            }
        } else {
            // Kiểm tra loại ghế (ghế đơn)
            if (
                selectedSeatTypeId &&
                selectedSeatTypeId !== seatTypeId &&
                $(".ghe-chieu.selected").length > 0
            ) {
                alert("Bạn chỉ có thể chọn ghế cùng loại!");
                // Hủy chọn ghế
                cancelSeat($(this), seatId);
                return;
            }

            // Kiểm tra số lượng ghế (ghế đơn)
            if (
                !$(this).hasClass("selected") &&
                selectedSeatsCount + 1 > maxSeats
            ) {
                alert("Bạn not thể chọn quá 10 ghế!");
                // Hủy chọn ghế
                cancelSeat($(this), seatId);
                return;
            }

            selectedSeatTypeId = seatTypeId;
            const isSelected = $(this).hasClass("selected");
            $(this).toggleClass("selected");

            $.ajax({
                url: isSelected ? "/huy-chon-ghe" : "/chon-ghe",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: {
                    ghe_id: seatId,
                },
                success: () => {
                    console.log(
                        `Ghế ${seatId} ${
                            isSelected ? "đã được hủy" : "đã được chọn"
                        }`
                    );
                },
                error: (error) => {
                    console.error(
                        `Lỗi khi ${isSelected ? "hủy" : "chọn"} ghế ${seatId}:`,
                        error
                    );
                },
            });
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

        // Hiển thị loading
        $(this)
            .prop("disabled", true)
            .html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');

        // Chuẩn bị dữ liệu đặt vé
        const formData = new FormData();
        formData.append(
            "suat_chieu_id",
            $('input[name="suat_chieu_id"]').val()
        );

        // Thêm ghế đã chọn
        $(".ghe-chieu.selected").each(function () {
            formData.append("ghe_ids[]", $(this).data("seat-id"));
        });

        // Thêm đồ ăn
        $('input[name^="do_an"]').each(function () {
            const quantity = parseInt($(this).val()) || 0;
            if (quantity > 0) {
                const doAnId = $(this).attr("name").match(/\d+/)[0];
                formData.append(`do_an[${doAnId}]`, quantity);
            }
        });

        // Thêm combo
        $('input[name^="combo"]').each(function () {
            const quantity = parseInt($(this).val()) || 0;
            if (quantity > 0) {
                const comboId = $(this).attr("name").match(/\d+/)[0];
                formData.append(`combo[${comboId}]`, quantity);
            }
        });

        // Gửi request đặt vé
        $.ajax({
            url: "/dat-ve",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    // Chuyển đến trang thanh toán
                    window.location.href = response.redirect_url;
                } else {
                    alert(response.message || "Có lỗi xảy ra!");
                    resetBookingButton();
                }
            },
            error: function (xhr) {
                const response = xhr.responseJSON;
                alert(response?.message || "Có lỗi xảy ra khi đặt vé!");
                resetBookingButton();
            },
        });
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
