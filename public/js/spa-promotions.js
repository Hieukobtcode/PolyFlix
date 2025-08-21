// SPA Promotions JavaScript
console.log("🚀 SPA Promotions script loading...");

// Global state
let currentFilters = {
    search: new URLSearchParams(window.location.search).get("search") || "",
    ap_dung_cho:
        new URLSearchParams(window.location.search).get("ap_dung_cho") || "",
};

let isLoading = false;
let currentScrollPosition = 0;

console.log("Initial state:", {
    currentFilters,
    isLoading,
    currentScrollPosition,
});

// Filter functionality
window.setFilter = function (value) {
    console.log("🎯 setFilter called with value:", value);

    // Clear any existing notifications first
    clearAllNotifications();

    if (isLoading) {
        console.log("⏳ Already loading, skipping...");
        return;
    }

    // Store current scroll position for smooth navigation
    currentScrollPosition = window.pageYOffset;

    // Update filters
    currentFilters.ap_dung_cho = value;

    // Load content via AJAX
    if (typeof loadPromotions === "function") {
        loadPromotions(currentFilters, true);
    } else {
        console.error("❌ loadPromotions function not found!");
        // Fallback to regular form submission without alert
        const form = document.getElementById("filter-form");
        if (form) {
            const hiddenInput = document.getElementById("ap_dung_cho");
            if (hiddenInput) {
                hiddenInput.value = value;
                form.submit();
            }
        }
    }
};

// Main AJAX function
function loadPromotions(filters, updateHistory = true) {
    console.log("📡 loadPromotions called with filters:", filters);

    if (isLoading) {
        console.log("⏳ Already loading, skipping...");
        return;
    }

    isLoading = true;
    showLoadingState();

    // Build URL for API request
    const apiUrl = "/api/promotions?" + new URLSearchParams(filters).toString();
    console.log("🌐 API URL:", apiUrl);

    fetch(apiUrl, {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
        },
    })
        .then((response) => {
            console.log("📥 Response received:", response.status);
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            console.log("✅ Data received:", data);
            if (data.success) {
                // Update content
                updatePromotionsGrid(data.data.promotions);
                updatePagination(data.data.pagination);

                // Update URL and history
                if (updateHistory) {
                    const newUrl = buildURL(filters);
                    history.pushState(
                        {
                            filters: filters,
                            scrollPosition: currentScrollPosition,
                        },
                        "",
                        newUrl
                    );
                    console.log("🔗 URL updated:", newUrl);
                }

                // Update UI state
                updateUIFromFilters();

                // Restore scroll position smoothly
                if (currentScrollPosition > 0) {
                    setTimeout(() => {
                        window.scrollTo({
                            top: currentScrollPosition,
                            behavior: "smooth",
                        });
                        currentScrollPosition = 0;
                    }, 50);
                }

                // Only show notification for search/filter actions, not navigation
                if (filters.search || filters.ap_dung_cho) {
                    // Don't show notification for simple filter changes
                    console.log("✅ Content updated successfully");
                }
            } else {
                throw new Error(data.message || "Có lỗi xảy ra");
            }
        })
        .catch((error) => {
            console.error("❌ Error loading promotions:", error);
            // Only show error notification for critical errors, not navigation
            if (error.message && !error.message.includes("navigation")) {
                console.warn(
                    "⚠️ Failed to load promotions, falling back to page reload"
                );
                // Fallback to regular page navigation
                window.location.href = buildURL(filters);
            }
        })
        .finally(() => {
            hideLoadingState();
            isLoading = false;
            console.log("🏁 Loading completed");
        });
}

// Utility functions
function buildURL(filters) {
    const params = new URLSearchParams();
    Object.keys(filters).forEach((key) => {
        if (filters[key] && filters[key] !== "") {
            params.set(key, filters[key]);
        }
    });

    const queryString = params.toString();
    return "/promotions" + (queryString ? "?" + queryString : "");
}

function updateUIFromFilters() {
    console.log("🎨 Updating UI from filters:", currentFilters);

    // Update search input
    const searchInput = document.querySelector(".search-input");
    if (searchInput) {
        searchInput.value = currentFilters.search || "";
    }

    // Update tab buttons
    document.querySelectorAll(".tab-btn").forEach((btn) => {
        btn.classList.remove("active");
    });

    const activeFilter = currentFilters.ap_dung_cho || "";
    const activeBtn = document.querySelector(
        `[onclick="setFilter('${activeFilter}')"]`
    );
    if (activeBtn) {
        activeBtn.classList.add("active");
    }

    // Update hidden input
    const hiddenInput = document.getElementById("ap_dung_cho");
    if (hiddenInput) {
        hiddenInput.value = activeFilter;
    }
}

function showLoadingState() {
    console.log("⏳ Showing loading state...");

    const gridContainer = document.querySelector(".grid-container");
    if (gridContainer) {
        gridContainer.style.opacity = "0.6";
        gridContainer.style.pointerEvents = "none";
    }

    // Add loading overlay
    const loadingOverlay = document.createElement("div");
    loadingOverlay.className = "loading-overlay";
    loadingOverlay.innerHTML = `
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <span>Đang tải...</span>
        </div>
    `;
    loadingOverlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.3s ease;
    `;

    document.body.appendChild(loadingOverlay);

    setTimeout(() => {
        loadingOverlay.style.opacity = "1";
    }, 10);
}

function hideLoadingState() {
    console.log("✅ Hiding loading state...");

    const gridContainer = document.querySelector(".grid-container");
    if (gridContainer) {
        gridContainer.style.opacity = "1";
        gridContainer.style.pointerEvents = "auto";
    }

    const loadingOverlay = document.querySelector(".loading-overlay");
    if (loadingOverlay) {
        loadingOverlay.style.opacity = "0";
        setTimeout(() => {
            loadingOverlay.remove();
        }, 300);
    }
}

// Clear all notifications function
function clearAllNotifications() {
    // Remove custom notifications
    document
        .querySelectorAll(".custom-notification")
        .forEach((n) => n.remove());

    // Remove any debug notifications
    document
        .querySelectorAll("[style*='position: fixed'][style*='z-index: 99999']")
        .forEach((n) => n.remove());

    // Clear browser notifications if any
    if (
        typeof Notification !== "undefined" &&
        Notification.permission === "granted"
    ) {
        // Close any open notifications (this is limited by browser security)
        console.log("🧹 Cleared notifications");
    }
}

function showNotification(message, type = "info") {
    console.log("📢 Showing notification:", message, type);

    // Remove existing notifications
    clearAllNotifications();

    const notification = document.createElement("div");
    notification.className = `custom-notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${
                type === "success"
                    ? "check-circle"
                    : type === "error"
                    ? "exclamation-circle"
                    : "info-circle"
            }"></i>
            <span>${message}</span>
        </div>
    `;

    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${
            type === "success"
                ? "linear-gradient(135deg, #00b894 0%, #00cec9 100%)"
                : type === "error"
                ? "linear-gradient(135deg, #e17055 0%, #d63031 100%)"
                : "linear-gradient(135deg, #667eea 0%, #764ba2 100%)"
        };
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        z-index: 10000;
        transform: translateX(400px);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    `;

    notification.querySelector(".notification-content").style.cssText = `
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        font-size: 14px;
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.style.transform = "translateX(0)";
    }, 100);

    // Auto remove
    setTimeout(() => {
        notification.style.transform = "translateX(400px)";
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Helper function to create a single promotion card
function createPromotionCard(promotion) {
    const discountValue =
        promotion.loai_giam_gia === "phan_tram"
            ? `${promotion.gia_tri_giam}%`
            : `${(promotion.gia_tri_giam / 1000).toFixed(0)}K`;

    const iconClass =
        promotion.ap_dung_cho === "ve"
            ? "fa-ticket-alt"
            : promotion.ap_dung_cho === "do_an"
            ? "fa-utensils"
            : "fa-gift";

    const typeText =
        promotion.ap_dung_cho === "ve"
            ? "VÉ PHIM"
            : promotion.ap_dung_cho === "do_an"
            ? "ĐỒ ĂN"
            : "COMBO";

    const donToiThieuHtml =
        promotion.don_toi_thieu > 0
            ? `
        <div class="detail-item">
            <i class="fas fa-money-bill-wave"></i>
            <span>Đơn tối thiểu ${new Intl.NumberFormat("vi-VN").format(
                promotion.don_toi_thieu
            )}đ</span>
        </div>`
            : "";

    const detailUrl = `/promotions/${promotion.id}`;

    return `
        <div class="promotion-card-wrapper">
            <div class="promotion-card">
                <div class="discount-badge">
                    <span class="discount-value">${discountValue}</span>
                    <span class="discount-label">GIẢM</span>
                </div>
                <div class="card-header">
                    <div class="promotion-icon">
                        <i class="fas ${iconClass}"></i>
                    </div>
                    <div class="promotion-type">${typeText}</div>
                </div>
                <div class="card-content">
                    <h3 class="promotion-title">${promotion.ten}</h3>
                    <p class="promotion-description">${
                        promotion.mo_ta.length > 100
                            ? promotion.mo_ta.substring(0, 100) + "..."
                            : promotion.mo_ta
                    }</p>
                    <div class="promotion-details">
                        <div class="detail-item">
                            <i class="fas fa-tag"></i>
                            <span>${promotion.ma_khuyen_mai}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>${new Date(
                                promotion.ngay_ket_thuc
                            ).toLocaleDateString("vi-VN")}</span>
                        </div>
                        ${donToiThieuHtml}
                    </div>
                </div>
                <div class="card-actions">
                    <button class="copy-btn" data-code="${
                        promotion.ma_khuyen_mai
                    }">
                        <i class="fas fa-copy"></i>
                        <span>Sao chép mã</span>
                    </button>
                    <a href="${detailUrl}" class="detail-btn">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    `;
}

// Function to update the promotions grid
function updatePromotionsGrid(promotions) {
    console.log(
        "🔄 updatePromotionsGrid called with",
        promotions.length,
        "promotions"
    );
    const gridContainer = document.querySelector(".grid-container");
    if (!gridContainer) {
        console.error("❌ Grid container not found!");
        return;
    }

    // Clear existing content
    gridContainer.innerHTML = "";

    if (promotions.length > 0) {
        promotions.forEach((promotion) => {
            const cardHtml = createPromotionCard(promotion);
            gridContainer.insertAdjacentHTML("beforeend", cardHtml);
        });
    } else {
        // Show empty state
        gridContainer.innerHTML = `
            <div class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                <div class="empty-icon" style="font-size: 4rem; color: #a0aec0; margin-bottom: 20px;">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="empty-title" style="font-size: 1.5rem; font-weight: 600; margin-bottom: 10px;">Không tìm thấy khuyến mãi</h3>
                <p class="empty-description" style="color: #718096; margin-bottom: 20px;">Hãy thử tìm kiếm với từ khóa khác hoặc thay đổi bộ lọc.</p>
                <a href="/promotions" class="empty-action" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    <i class="fas fa-sync-alt"></i>
                    Xem tất cả khuyến mãi
                </a>
            </div>
        `;
    }
}

// Function to update pagination
function updatePagination(pagination) {
    console.log("📄 updatePagination called with", pagination);
    const paginationWrapper = document.querySelector(".pagination-wrapper");
    if (!paginationWrapper) {
        console.warn("⚠️ Pagination wrapper not found!");
        return;
    }

    if (pagination && pagination.links) {
        paginationWrapper.innerHTML = pagination.links;
        paginationWrapper.style.display = "flex";
    } else {
        paginationWrapper.innerHTML = "";
        paginationWrapper.style.display = "none";
    }
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    console.log("🎉 DOM loaded, initializing SPA...");

    // Initialize browser history
    history.replaceState(
        {
            filters: currentFilters,
            scrollPosition: 0,
        },
        "",
        buildURL(currentFilters)
    );

    // Handle browser back/forward buttons
    window.addEventListener("popstate", function (event) {
        console.log("🔙 Popstate event:", event.state);
        if (event.state && event.state.filters) {
            currentFilters = event.state.filters;
            updateUIFromFilters();
            loadPromotions(currentFilters, false);
        }
    });

    console.log("✅ SPA initialization complete!");
});

// Clear notifications when navigating away from page
window.addEventListener("beforeunload", function () {
    clearAllNotifications();
});

// Clear notifications when page visibility changes (tab switching)
document.addEventListener("visibilitychange", function () {
    if (document.hidden) {
        clearAllNotifications();
    }
});

// Clear notifications on any navigation event
window.addEventListener("popstate", function () {
    clearAllNotifications();
});

// Clear notifications when clicking on navigation links
document.addEventListener("DOMContentLoaded", function () {
    // Clear notifications when clicking any navigation link
    document.querySelectorAll("a[href]").forEach((link) => {
        link.addEventListener("click", function () {
            // Small delay to allow the click to register
            setTimeout(clearAllNotifications, 10);
        });
    });

    // Clear notifications when form is submitted
    document.querySelectorAll("form").forEach((form) => {
        form.addEventListener("submit", function () {
            clearAllNotifications();
        });
    });
});

console.log("🎯 SPA Promotions script loaded successfully!");
console.log("Available functions:", {
    setFilter: typeof window.setFilter,
    loadPromotions: typeof loadPromotions,
    showNotification: typeof showNotification,
    clearAllNotifications: typeof clearAllNotifications,
});
