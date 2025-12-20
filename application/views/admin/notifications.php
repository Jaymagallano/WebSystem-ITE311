<?php $this->load->view('templates/header', ['page_title' => 'My Notifications']); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-bell-fill me-2"></i>All Notifications</h4>
                <button id="mark-all-read-page" class="btn btn-sm btn-light">
                    <i class="bi bi-check2-all me-1"></i>Mark All as Read
                </button>
            </div>
            <div class="card-body">
                <div id="notifications-container">
                    <!-- Notifications will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>

<script>
$(document).ready(function() {
    const baseUrl = typeof window.base_url !== 'undefined' ? window.base_url : 
                   (window.location.origin + '/ITE311-MAGALLANO/');
    
    // Load all notifications on page load
    loadAllNotifications();
    
    // Mark all as read
    $('#mark-all-read-page').on('click', function() {
        markAllAsReadPage();
    });
    
    /**
     * Load all notifications via AJAX
     */
    function loadAllNotifications() {
        $.ajax({
            url: baseUrl + 'notifications/get_all',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                renderAllNotifications(response.notifications);
            },
            error: function(xhr, status, error) {
                console.error('Error loading notifications:', error);
                $('#notifications-container').html(`
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Error loading notifications. Please try again.
                    </div>
                `);
            }
        });
    }
    
    /**
     * Render all notifications
     */
    function renderAllNotifications(notifications) {
        const container = $('#notifications-container');
        
        if (notifications.length === 0) {
            container.html(`
                <div class="text-center py-5">
                    <i class="bi bi-bell-slash fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">No notifications yet</h5>
                    <p class="text-muted">You don't have any notifications at the moment.</p>
                </div>
            `);
            return;
        }
        
        let html = '<div class="notification-list">';
        
        notifications.forEach(function(notif) {
            const iconClass = getNotificationIcon(notif.type);
            const link = notif.link ? baseUrl + notif.link : '#';
            const statusClass = notif.is_read ? '' : 'unread';
            const timeClass = notif.is_read ? 'text-muted' : '';
            
            html += `
                <div class="notification-item ${statusClass}" data-id="${notif.id}" data-link="${link}">
                    <div class="d-flex">
                        <div class="notification-icon ${notif.type}">
                            <i class="bi ${iconClass}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="notification-title">${notif.title}</div>
                            <div class="notification-message">${notif.message}</div>
                            <div class="notification-time ${timeClass}">
                                <i class="bi bi-clock"></i> ${notif.time_ago}
                                ${notif.is_read ? '<span class="badge bg-secondary ms-2">Read</span>' : '<span class="badge bg-primary ms-2">Unread</span>'}
                            </div>
                        </div>
                        <div>
                            ${notif.is_read ? '' : `<button class="btn btn-sm btn-outline-primary mark-read-btn" data-id="${notif.id}">Mark Read</button>`}
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        container.html(html);
        
        // Add click handlers to notification items
        $('.notification-item').on('click', function(e) {
            if (!$(e.target).hasClass('mark-read-btn')) {
                const link = $(this).data('link');
                if (link && link !== '#') {
                    window.location.href = link;
                }
            }
        });
        
        // Add click handlers to mark read buttons
        $('.mark-read-btn').on('click', function(e) {
            e.stopPropagation();
            const notifId = $(this).data('id');
            markAsReadPage(notifId);
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
    function markAsReadPage(notificationId) {
        $.ajax({
            url: baseUrl + 'notifications/mark_read/' + notificationId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    loadAllNotifications(); // Refresh notifications
                    showToast('Notification marked as read');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error marking as read:', error);
                showToast('Error marking notification as read', 'danger');
            }
        });
    }
    
    /**
     * Mark all notifications as read
     */
    function markAllAsReadPage() {
        $.ajax({
            url: baseUrl + 'notifications/mark_all_read',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    loadAllNotifications(); // Refresh notifications
                    showToast('All notifications marked as read');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error marking all as read:', error);
                showToast('Error marking all notifications as read', 'danger');
            }
        });
    }
    
    /**
     * Show toast notification
     */
    function showToast(message, type = 'success') {
        const bgColor = type === 'success' ? '#2f855a' : '#c53030';
        const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
        
        // Create toast element if it doesn't exist
        if ($('#notification-toast').length === 0) {
            $('body').append(`
                <div id="notification-toast" style="
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    background: ${bgColor};
                    color: white;
                    padding: 12px 20px;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    display: none;
                    z-index: 10000;
                    font-weight: 500;
                ">
                    <i class="bi ${icon} me-2"></i>
                    <span id="toast-message"></span>
                </div>
            `);
        } else {
            $('#notification-toast').css('background', bgColor);
            $('#notification-toast i').attr('class', `bi ${icon} me-2`);
        }
        
        $('#toast-message').text(message);
        $('#notification-toast').fadeIn(300).delay(2000).fadeOut(300);
    }
});
</script>