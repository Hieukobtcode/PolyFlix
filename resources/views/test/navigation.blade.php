@extends('layouts.client')

@section('title', 'Test Navigation - PolyFlix')

@section('content')
<div style="padding: 40px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div style="max-width: 1200px; margin: 0 auto; background: white; border-radius: 20px; padding: 40px; color: #333;">
        <h1 style="text-align: center; margin-bottom: 40px; color: #333;">🧪 Test Navigation & Notifications</h1>
        
        <!-- Navigation Test Section -->
        <div style="background: #f8f9fa; padding: 30px; border-radius: 15px; margin-bottom: 30px;">
            <h2 style="margin-bottom: 20px; color: #495057;">📍 Navigation Links Test</h2>
            <p style="margin-bottom: 20px; color: #6c757d;">Click these links to test navigation behavior and notification clearing:</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <a href="{{ route('client.khuyen-mai.index') }}" 
                   style="display: block; padding: 15px; background: #007bff; color: white; text-align: center; border-radius: 8px; text-decoration: none; transition: all 0.3s;">
                    🎯 Khuyến Mãi
                </a>
                <a href="{{ route('client.home') }}" 
                   style="display: block; padding: 15px; background: #28a745; color: white; text-align: center; border-radius: 8px; text-decoration: none; transition: all 0.3s;">
                    🏠 Trang Chủ
                </a>
                <a href="{{ route('client.phim.index') }}" 
                   style="display: block; padding: 15px; background: #dc3545; color: white; text-align: center; border-radius: 8px; text-decoration: none; transition: all 0.3s;">
                    🎬 Phim
                </a>
                <a href="{{ route('client.do-an.index') }}" 
                   style="display: block; padding: 15px; background: #fd7e14; color: white; text-align: center; border-radius: 8px; text-decoration: none; transition: all 0.3s;">
                    🍿 Đồ Ăn
                </a>
            </div>
        </div>

        <!-- Notification Test Section -->
        <div style="background: #e9ecef; padding: 30px; border-radius: 15px; margin-bottom: 30px;">
            <h2 style="margin-bottom: 20px; color: #495057;">🔔 Notification Test</h2>
            <p style="margin-bottom: 20px; color: #6c757d;">Test different types of notifications and their clearing behavior:</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
                <button onclick="showTestNotification('success')" 
                        style="padding: 15px; background: #28a745; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    ✅ Success Notification
                </button>
                <button onclick="showTestNotification('error')" 
                        style="padding: 15px; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    ❌ Error Notification
                </button>
                <button onclick="showTestNotification('info')" 
                        style="padding: 15px; background: #17a2b8; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    ℹ️ Info Notification
                </button>
                <button onclick="showTestAlert()" 
                        style="padding: 15px; background: #ffc107; color: #212529; border: none; border-radius: 8px; cursor: pointer;">
                    ⚠️ Browser Alert
                </button>
            </div>
            
            <button onclick="clearAllNotifications()" 
                    style="width: 100%; padding: 15px; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer; margin-bottom: 10px;">
                🧹 Clear All Notifications
            </button>
        </div>

        <!-- Scroll Test Section -->
        <div style="background: #d1ecf1; padding: 30px; border-radius: 15px; margin-bottom: 30px;">
            <h2 style="margin-bottom: 20px; color: #495057;">📜 Scroll Behavior Test</h2>
            <p style="margin-bottom: 20px; color: #6c757d;">Test scroll position preservation during navigation:</p>
            
            <div style="height: 300px; overflow-y: auto; background: white; border-radius: 8px; padding: 20px; border: 2px solid #bee5eb;">
                <h3>Scrollable Content</h3>
                @for($i = 1; $i <= 50; $i++)
                    <p style="margin: 10px 0; padding: 10px; background: {{ $i % 2 == 0 ? '#f8f9fa' : '#ffffff' }}; border-radius: 4px;">
                        📝 Line {{ $i }}: This is test content to demonstrate scroll behavior. 
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    </p>
                @endfor
            </div>
            
            <div style="margin-top: 15px; text-align: center;">
                <button onclick="scrollToTop()" 
                        style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin: 0 5px;">
                    ⬆️ Scroll to Top
                </button>
                <button onclick="scrollToMiddle()" 
                        style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; margin: 0 5px;">
                    ➡️ Scroll to Middle
                </button>
                <button onclick="scrollToBottom()" 
                        style="padding: 10px 20px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; margin: 0 5px;">
                    ⬇️ Scroll to Bottom
                </button>
            </div>
        </div>

        <!-- Test Results -->
        <div style="background: #fff3cd; padding: 30px; border-radius: 15px;">
            <h2 style="margin-bottom: 20px; color: #856404;">📊 Test Instructions</h2>
            <ol style="color: #856404; line-height: 1.8;">
                <li><strong>Notification Test:</strong> Click notification buttons, then click navigation links to verify notifications are cleared</li>
                <li><strong>Scroll Test:</strong> Scroll to different positions, then navigate to other pages to check scroll behavior</li>
                <li><strong>Filter Test:</strong> Go to Khuyến Mãi page, click filter buttons, then navigate away to test notification clearing</li>
                <li><strong>Smooth Navigation:</strong> Verify that page transitions are smooth without jarring effects</li>
                <li><strong>No Unwanted Alerts:</strong> Ensure no debug alerts or popups appear during normal navigation</li>
            </ol>
        </div>
    </div>
</div>

<script>
// Test notification functions
function showTestNotification(type) {
    if (typeof showNotification === 'function') {
        const messages = {
            success: 'This is a success notification!',
            error: 'This is an error notification!',
            info: 'This is an info notification!'
        };
        showNotification(messages[type] || 'Test notification', type);
    } else {
        // Fallback notification
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'};
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            z-index: 10000;
            font-weight: bold;
        `;
        notification.textContent = `Test ${type} notification`;
        document.body.appendChild(notification);
        
        setTimeout(() => notification.remove(), 3000);
    }
}

function showTestAlert() {
    alert('This is a browser alert - should be cleared on navigation!');
}

// Scroll functions
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function scrollToMiddle() {
    window.scrollTo({ top: document.body.scrollHeight / 2, behavior: 'smooth' });
}

function scrollToBottom() {
    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
}

// Log current scroll position
window.addEventListener('scroll', function() {
    console.log('Current scroll position:', window.pageYOffset);
});

console.log('🧪 Navigation test page loaded');
</script>
@endsection
