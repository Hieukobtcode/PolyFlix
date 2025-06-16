// 1. Import jQuery đầu tiên nếu các script sau cần dùng $
import $ from "jquery";
window.$ = $;
window.jQuery = $;

// 2. Import Bootstrap setup (nếu bootstrap dùng jQuery thì jQuery phải có trước)
import "./bootstrap";

// 3. Import Select2 và CSS
import "select2";
import "select2/dist/css/select2.min.css";

// 4. Import custom CSS (tuỳ bạn có thể để đầu hoặc cuối)
import "../css/ghe-ngoi.css";

// 5. Import custom JS sau cùng (vì các thư viện cần load trước)
import "./ghe-ngoi";
