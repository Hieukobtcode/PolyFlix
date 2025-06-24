import $ from "jquery";
window.$ = $;
window.jQuery = $;
import "../css/trang-chu.css";
$(".movie-select").on("change", function () {
    $(this).find("option:first").hide(); 
});
