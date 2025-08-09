import "../css/chat.css";
import $ from "jquery";

// Gắn jQuery vào window
window.$ = $;
window.jQuery = $;

let isWaiting = false;

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
});

window.toggleChat = function () {
    const chatbox = document.getElementById("chatbox-ai");
    const chatMessages = document.getElementById("chat-messages");

    chatbox.classList.toggle("active");

    if (chatbox.classList.contains("active") && chatMessages.children.length === 0) {
        appendAIMessage("<em>Tôi có thể giúp gì cho bạn?</em>");
    }
};

window.sendChat = function () {
    const input = document.getElementById("chat-input");
    const text = input.value.trim();

    if (!text || isWaiting) return;

    appendUserMessage(text);
    input.value = "";

    isWaiting = true;
    input.disabled = true;

    appendTypingIndicator();

    $.post("/ai-chat", { message: text }, function (res) {
        removeTypingIndicator();

        if (res.reply) {
            appendAIMessage(res.reply);
        } else {
            appendAIMessage("❌ <em>AI không trả lời được.</em>");
        }
    })
    .fail(() => {
        removeTypingIndicator();
        appendAIMessage("❌ <em>Đã xảy ra lỗi khi kết nối máy chủ.</em>");
    })
    .always(() => {
        isWaiting = false;
        input.disabled = false;
        input.focus();
    });
};

function appendUserMessage(text) {
    const chat = document.getElementById("chat-messages");

    const div = document.createElement("div");
    div.className = "chat-bubble user";

    div.innerHTML = `
        <div class="avatar">👤</div>
        <div class="bubble-content">${escapeHTML(text)}</div>
    `;

    chat.appendChild(div);
    scrollToBottom(chat);
}

function appendAIMessage(html) {
    const chat = document.getElementById("chat-messages");

    const div = document.createElement("div");
    div.className = "chat-bubble ai";

    div.innerHTML = `
        <div class="avatar">🤖</div>
        <div class="bubble-content">${html}</div>
    `;

    chat.appendChild(div);
    scrollToBottom(chat);
}

function appendTypingIndicator() {
    const chat = document.getElementById("chat-messages");

    const div = document.createElement("div");
    div.className = "chat-bubble ai typing-indicator";
    div.innerHTML = `
        <div class="avatar">🤖</div>
        <div class="bubble-content">
            <span class="dots"><span>.</span><span>.</span><span>.</span></span>
        </div>
    `;

    chat.appendChild(div);
    scrollToBottom(chat);
}

function removeTypingIndicator() {
    const typing = document.querySelector(".typing-indicator");
    if (typing) typing.remove();
}

function scrollToBottom(container) {
    container.scrollTop = container.scrollHeight;
}

function escapeHTML(str) {
    const div = document.createElement("div");
    div.textContent = str;
    return div.innerHTML;
}
