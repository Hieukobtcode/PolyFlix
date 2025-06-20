import "../css/login.css";
import $ from "jquery";
window.$ = $;
window.jQuery = $;

$(document).ready(function () {
    window.showTab = function (tabId) {
        $(".tab").removeClass("active");
        $(".tab-pane").removeClass("show slide-down slide-up");

        $(`.tab[data-tab="${tabId}"]`).addClass("active");

        const targetPane = $(`#${tabId}`);
        targetPane.addClass("show");

        if (tabId === "login") {
            targetPane.addClass("slide-down");
        } else if (tabId === "register") {
            targetPane.addClass("slide-up");
        }
    };

    $(".tab").on("click", function () {
        const tabId = $(this).data("tab");
        showTab(tabId);
    });

    $(".toggle-password").on("click", function () {
        const target = $(this).data("target");
        const input = $(target);
        const type = input.attr("type") === "password" ? "text" : "password";

        input.attr("type", type);
        $(this).html(
            type === "password"
                ? '<i class="fa-solid fa-eye-slash" style="color: #B197FC;"></i>'
                : '<i class="fa-solid fa-eye" style="color: #B197FC;"></i>'
        );
    });
});
