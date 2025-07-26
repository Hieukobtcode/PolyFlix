import "../css/chat.css";
import $ from "jquery";

// Gắn jQuery vào window
window.$ = $;
window.jQuery = $;

// Cấu hình CSRF token cho Ajax Laravel
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
    },
});

// Hàm mở/đóng chatbox
window.toggleChat = function () {
    const chatbox = document.getElementById("chatbox-ai");
    const chatMessages = document.getElementById("chat-messages");

    chatbox.classList.toggle("active");

    if (
        chatbox.classList.contains("active") &&
        chatMessages.children.length === 0
    ) {
        appendAIMessage("🤖 Tôi có thể giúp gì cho bạn?");
    }
};

// Hàm gửi tin nhắn
window.sendChat = function () {
    const input = document.getElementById("chat-input");
    const text = input.value.trim();
    if (!text) return;

    appendUserMessage(text);
    input.value = "";

    // Gửi lên server Laravel
    $.post("/ai-chat", { message: text }, function (res) {
        console.log(res); 
        if (res.reply) {
            appendAIMessage("🤖 " + res.reply);
        } else {
            appendAIMessage("❌ AI không trả lời được.");
        }
    }).fail(() => {
        appendAIMessage("❌ Đã xảy ra lỗi khi kết nối máy chủ.");
    });
};

// Hiển thị tin nhắn người dùng
function appendUserMessage(text) {
    const chat = document.getElementById("chat-messages");
    const div = document.createElement("div");
    div.className = "message user-message";
    div.textContent = text;
    chat.appendChild(div);
    chat.scrollTop = chat.scrollHeight;
}

// Hiển thị tin nhắn AI
function appendAIMessage(text) {
    const chat = document.getElementById("chat-messages");
    const div = document.createElement("div");
    div.className = "message ai-message";
    div.textContent = text;
    chat.appendChild(div);
    chat.scrollTop = chat.scrollHeight;
}
