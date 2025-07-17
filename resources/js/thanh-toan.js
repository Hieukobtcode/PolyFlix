import $ from "jquery";
window.$ = $;
window.jQuery = $;

$(document).ready(function () {
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

        $.ajax({
            url: "/thanh-toan/xu-ly", 
            method: "POST",
            data: {
                phuong_thuc_tt: paymentMethod,
                dat_ve_id: datVeId,
            },
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {
                console.log(response);

                Swal.fire({
                    icon: "success",
                    title: "Thành công",
                    text: "Đã xử lý thanh toán!",
                }).then(() => {
                    if (response.redirect_url) {
                        window.location.href = response.redirect_url;
                    }
                });
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
});
