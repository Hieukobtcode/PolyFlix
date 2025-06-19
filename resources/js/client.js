import "../css/client.css";
import $ from "jquery";
window.$ = $;
window.jQuery = $;
$(document).ready(function () {
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
