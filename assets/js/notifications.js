/**
 * Real-time Notification System
 * Uses jQuery AJAX to fetch and display notifications
 */

// Wait for DOM to be fully loaded
$(document).ready(function() {
    // Small delay to ensure all elements are rendered
    setTimeout(function() {
        initializeNotificationSystem();
    }, 100);
});

function initializeNotificationSystem() {
    // More robust base URL detection
    const baseUrl = typeof window.base_url !== 'undefined' ? window.base_url : 
                   (window.location.origin + '/ITE311-MAGALLANO/').replace(/\/$/, '') + '/';    
    // Debug information
    console.log('Notification System Initialized');
    console.log('Base URL:', baseUrl);
    
    // Load notifications on page load
    loadNotifications();
    
    // Auto-refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);
    
    // When the Bootstrap dropdown is toggled, refresh notifications
    $(document).on('click', '#notificationDropdown', function (e) {
        // Let Bootstrap handle open/close; just load fresh data
        loadNotifications();
    });

    // Also bind click to the bell icon itself
    $(document).on('click', '#notificationDropdown i', function (e) {
        // Delegate to the parent anchor
        $('#notificationDropdown').trigger('click');
    });
    
    // Mark all as read
    $(document).on('click', '#mark-all-read', function(e) {
        e.preventDefault();
        e.stopPropagation();
        markAllAsRead();
    });
    
    // View all notifications
    $(document).on('click', '#view-all-notifications', function(e) {
        e.preventDefault();
        e.stopPropagation();
        viewAllNotifications();
    });
    
    /**
     * Load notifications via AJAX
     */
    function loadNotifications() {
        console.log('Loading notifications from:', baseUrl + 'notifications/get_unread');
        $.ajax({
            url: baseUrl + 'notifications/get_unread',
            method: 'GET',
            dataType: 'json',
            xhrFields: {
                withCredentials: true
            },
            success: function(response) {
                console.log('Notifications loaded successfully:', response);
                updateNotificationBadge(response.count);
                renderNotifications(response.notifications);
            },
            error: function(xhr, status, error) {
                console.error('Error loading notifications:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
            }
        });
    }
    
    /**
     * Update notification badge count
     */
    function updateNotificationBadge(count) {
        const badge = $('#notification-count');
        
        if (count > 0) {
            badge.text(count > 99 ? '99+' : count);
            badge.show();
        } else {
            badge.hide();
        }
    }
    
    /**
     * Render notifications in dropdown
     */
    function renderNotifications(notifications) {
        const container = $('#notification-list');
        
        if (notifications.length === 0) {
            container.html(`
                <div class="empty-notifications">
                    <i class="bi bi-bell-slash"></i>
                    <p>No notifications yet</p>
                </div>
            `);
            return;
        }
        
        let html = '';
        
        notifications.forEach(function(notif) {
            const iconClass = getNotificationIcon(notif.type);
            // Ensure proper URL construction
            let link = '#';
            if (notif.link) {
                // If link is already a full URL, use it as is
                if (notif.link.startsWith('http')) {
                    link = notif.link;
                } else {
                    // Otherwise, prepend base URL
                    link = baseUrl + notif.link.replace(/^\/+/, '');
                }
            }
            
            html += `
                <div class="notification-item unread" data-id="${notif.id}" data-link="${link}">
                    <div class="d-flex">
                        <div class="notification-icon ${notif.type}">
                            <i class="bi ${iconClass}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="notification-title">${notif.title}</div>
                            <div class="notification-message">${notif.message}</div>
                            <div class="notification-time">
                                <i class="bi bi-clock"></i> ${notif.time_ago}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        container.html(html);
        
        // Add click handlers to notification items
        $('.notification-item').on('click', function(e) {
            e.stopPropagation();
            const notifId = $(this).data('id');
            const link = $(this).data('link');
            
            markAsRead(notifId, function() {
                if (link && link !== '#') {
                    window.location.href = link;
                }
            });
        });
    }
    
    /**
     * Get icon class for notification type
     */
    function getNotificationIcon(type) {
        const icons = {
            'material': 'bi-file-earmark-text-fill',
            'assignment': 'bi-clipboard-check-fill',
            'grade': 'bi-award-fill',
            'enrollment': 'bi-book-fill',
            'submission': 'bi-upload'
        };
        
        return icons[type] || 'bi-bell-fill';
    }
    
    /**
     * Mark single notification as read
     */
    function markAsRead(notificationId, callback) {
        console.log('Marking notification as read:', notificationId);
        $.ajax({
            url: baseUrl + 'notifications/mark_read/' + notificationId,
            method: 'GET',
            dataType: 'json',
            xhrFields: {
                withCredentials: true
            },
            success: function(response) {
                console.log('Mark as read response:', response);
                if (response.success) {
                    loadNotifications(); // Refresh notifications
                    if (callback) callback();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error marking as read:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
            }
        });
    }
    
    /**
     * Mark all notifications as read
     */
    function markAllAsRead() {
        console.log('Marking all notifications as read');
        $.ajax({
            url: baseUrl + 'notifications/mark_all_read',
            method: 'GET',
            dataType: 'json',
            xhrFields: {
                withCredentials: true
            },
            success: function(response) {
                console.log('Mark all as read response:', response);
                if (response.success) {
                    loadNotifications(); // Refresh notifications
                    
                    // Show success message
                    showToast('All notifications marked as read');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error marking all as read:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
            }
        });
    }
    
    /**
     * View all notifications
     */
    function viewAllNotifications() {
        // Let Bootstrap close the dropdown via data-bs-toggle
        $('.notification-dropdown').removeClass('show');
        // Redirect to notifications page
        window.location.href = baseUrl + 'notifications';
    }
    
    /**
     * Show toast notification (optional)
     */
    function showToast(message) {
        // Create toast element if it doesn't exist
        if ($('#notification-toast').length === 0) {
            $('body').append(`
                <div id="notification-toast" style="
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    background: linear-gradient(135deg, #2f855a 0%, #276749 100%);
                    color: white;
                    padding: 12px 20px;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    display: none;
                    z-index: 10000;
                    font-weight: 500;
                ">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <span id="toast-message"></span>
                </div>
            `);
        }
        
        $('#toast-message').text(message);
        $('#notification-toast').fadeIn(300).delay(2000).fadeOut(300);
    }
}