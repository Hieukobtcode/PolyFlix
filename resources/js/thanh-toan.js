import $ from "jquery";
window.$ = $;
window.jQuery = $;

$(document).ready(function () {
    // Biến lưu thông tin khuyến mãi
    let appliedPromotion = null;
    let originalTotal = 0;

    // Lấy tổng tiền ban đầu
    const finalTotalElement = document.getElementById("final-total");
    if (finalTotalElement) {
        originalTotal = parseInt(
            finalTotalElement.textContent.replace(/[^\d]/g, "")
        );
    }
    $(".payment-option").click(function () {
        $(".payment-option").removeClass("selected");

        $(this).addClass("selected");
        $(this).find('input[type="radio"]').prop("checked", true);

        $("#btn-pay")
            .prop("disabled", false)
            .html('<i class="fas fa-check-circle"></i> Tiến hành thanh toán');
    });

    $("#btn-pay").on("click", function (e) {
        e.preventDefault();

        if ($(this).prop("disabled")) return;

        const paymentMethod = $('input[name="phuong_thuc_tt"]:checked').val();
        const datVeId = $('input[name="dat_ve_id"]').val();
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        // Payload thanh toán; gửi kèm thông tin khuyến mãi nếu đã áp dụng
        const payload = {
            phuong_thuc_tt: paymentMethod,
            dat_ve_id: datVeId,
        };

        console.log("Applied promotion:", appliedPromotion);

        if (appliedPromotion) {
            payload.khuyen_mai_id = appliedPromotion.id;
            payload.ma_khuyen_mai = appliedPromotion.ma_khuyen_mai;
            payload.giam_gia = appliedPromotion.giam_gia;
            payload.tong_sau_giam = appliedPromotion.tong_sau_giam;
            console.log("Đã thêm thông tin khuyến mãi vào payload:", payload);
        } else {
            console.log("Không có khuyến mãi được áp dụng");
        }

        $.ajax({
            url: "/thanh-toan/xu-ly",
            method: "POST",
            data: payload,
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {
                console.log("ZaloPay response:", response);
                if (response.redirect_url) {
                    window.location.href = response.redirect_url;
                } else {
                    Swal.fire({
                        icon: "warning",
                        title: "Không có URL chuyển hướng",
                        text: "ZaloPay không trả về URL chuyển hướng",
                    });
                }
            },

            error: function (xhr) {
                console.error(xhr.responseText);

                Swal.fire({
                    icon: "error",
                    title: "Lỗi",
                    text: "Thanh toán thất bại. Vui lòng thử lại.",
                });
            },
        });
    });

    // Xử lý áp dụng khuyến mãi
    $("#apply-promotion").on("click", function () {
        const promotionCode = $("#promotion-code").val().trim();
        const messageDiv = $("#promotion-message");
        const discountDiv = $("#promotion-discount");
        const discountAmount = $("#discount-amount");
        const finalTotal = $("#final-total");

        if (!promotionCode) {
            showPromotionMessage("Vui lòng nhập mã khuyến mãi", "error");
            return;
        }

        // Disable button và hiển thị loading
        $(this)
            .prop("disabled", true)
            .html('<i class="fas fa-spinner fa-spin"></i> Đang kiểm tra...');

        // Lấy dat_ve_id từ input đầu tiên trong .payment-methods container
        const datVeId = $(
            '.payment-methods input[name="dat_ve_id"]:first'
        ).val();

        $.ajax({
            url: "/khuyen-mai/check-code",
            method: "POST",
            data: {
                ma_khuyen_mai: promotionCode,
                tong_tien: originalTotal,
                loai_san_pham: "ve", // Chỉ định đây là thanh toán vé phim
                dat_ve_id: datVeId,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    appliedPromotion = response.data;

                    // Hiển thị thông báo thành công
                    showPromotionMessage(response.message, "success");

                    // Hiển thị giảm giá
                    discountAmount.text(
                        "-" + formatNumber(appliedPromotion.giam_gia) + "đ"
                    );
                    discountDiv.show();

                    // Cập nhật tổng tiền
                    finalTotal.text(
                        formatNumber(appliedPromotion.tong_sau_giam) + "đ"
                    );

                    // Disable input và button
                    $("#promotion-code").prop("disabled", true);
                    $("#apply-promotion")
                        .html('<i class="fas fa-check"></i> Đã áp dụng')
                        .removeClass("btn-primary")
                        .addClass("btn-success");
                } else {
                    showPromotionMessage(response.message, "error");
                }
            },
            error: function (xhr) {
                showPromotionMessage(
                    "Có lỗi xảy ra. Vui lòng thử lại.",
                    "error"
                );
            },
            complete: function () {
                $("#apply-promotion").prop("disabled", false);
                if (!appliedPromotion) {
                    $("#apply-promotion").html(
                        '<i class="fas fa-check"></i> Áp dụng'
                    );
                }
            },
        });
    });

    // Hàm hiển thị thông báo khuyến mãi
    function showPromotionMessage(message, type) {
        const messageDiv = $("#promotion-message");
        messageDiv
            .removeClass("alert-success alert-danger")
            .addClass(
                type === "success"
                    ? "alert alert-success"
                    : "alert alert-danger"
            )
            .text(message)
            .show();

        if (type === "error") {
            setTimeout(() => {
                messageDiv.hide();
            }, 5000);
        }
    }

    // Hàm format số
    function formatNumber(num) {
        return new Intl.NumberFormat("vi-VN").format(num);
    }

    // Enter key support cho input khuyến mãi
    $("#promotion-code").on("keypress", function (e) {
        if (e.which === 13) {
            $("#apply-promotion").click();
        }
    });
});
