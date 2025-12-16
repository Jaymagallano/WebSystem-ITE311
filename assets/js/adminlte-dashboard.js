/**
 * AdminLTE Dashboard Functions
 * Interactive features for Admin, Teacher, and Student dashboards
 */

(function ($) {
    'use strict';

    // ============================================
    // DASHBOARD INITIALIZATION
    // ============================================
    const AdminLTEDashboard = {

        init: function () {
            this.initStatCards();
            this.initTooltips();
            this.initPopovers();
            this.initCardRefresh();
            this.initCardCollapse();
            this.initCountUp();
            this.initSearchFilter();
            this.initNotifications();
            this.initSidebarToggle();
            this.initSmoothScroll();
            this.initAlertAutoClose();
            this.initTableActions();
            this.initQuickActions();
            this.initLoadingStates();
            console.log('✅ AdminLTE Dashboard initialized');
        },

        // ============================================
        // STAT CARDS ANIMATION
        // ============================================
        initStatCards: function () {
            // Animations disabled
        },

        // ============================================
        // COUNT UP ANIMATION
        // ============================================
        initCountUp: function () {
            // Animations disabled
        },

        // ============================================
        // TOOLTIPS
        // ============================================
        initTooltips: function () {
            if (typeof bootstrap !== 'undefined') {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        },

        // ============================================
        // POPOVERS
        // ============================================
        initPopovers: function () {
            if (typeof bootstrap !== 'undefined') {
                const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
                popoverTriggerList.map(function (popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl);
                });
            }
        },

        // ============================================
        // CARD REFRESH
        // ============================================
        initCardRefresh: function () {
            $(document).on('click', '[data-card-widget="refresh"]', function (e) {
                e.preventDefault();
                const $card = $(this).closest('.card');
                const $icon = $(this).find('i');

                // Add loading state
                $icon.addClass('fa-spin');
                $card.css('opacity', '0.6');

                // Simulate refresh (replace with actual AJAX call)
                setTimeout(() => {
                    $icon.removeClass('fa-spin');
                    $card.css('opacity', '1');

                    // Show success message
                    AdminLTEDashboard.showToast('Card refreshed successfully!', 'success');
                }, 1500);
            });
        },

        // ============================================
        // CARD COLLAPSE
        // ============================================
        initCardCollapse: function () {
            $(document).on('click', '[data-card-widget="collapse"]', function (e) {
                e.preventDefault();
                const $card = $(this).closest('.card');
                const $body = $card.find('.card-body');
                const $icon = $(this).find('i');

                $body.slideToggle(300);
                $icon.toggleClass('bi-chevron-up bi-chevron-down');
            });
        },

        // ============================================
        // SEARCH FILTER
        // ============================================
        initSearchFilter: function () {
            $('#tableSearch').on('keyup', function () {
                const value = $(this).val().toLowerCase();
                $('.table tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        },

        // ============================================
        // NOTIFICATIONS
        // ============================================
        initNotifications: function () {
            // Mark notification as read
            $(document).on('click', '.notification-item', function () {
                $(this).removeClass('unread');
                $(this).find('.notification-badge').fadeOut();
            });

            // Clear all notifications
            $(document).on('click', '#clearNotifications', function (e) {
                e.preventDefault();
                $('.notification-item').fadeOut(300, function () {
                    $(this).remove();
                });
                AdminLTEDashboard.showToast('All notifications cleared', 'info');
            });
        },

        // ============================================
        // SIDEBAR TOGGLE
        // ============================================
        initSidebarToggle: function () {
            $(document).on('click', '[data-widget="pushmenu"]', function (e) {
                e.preventDefault();
                $('body').toggleClass('sidebar-collapse');

                // Save state to localStorage
                const isCollapsed = $('body').hasClass('sidebar-collapse');
                localStorage.setItem('sidebar-collapsed', isCollapsed);
            });

            // Restore sidebar state
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (isCollapsed) {
                $('body').addClass('sidebar-collapse');
            }
        },

        // ============================================
        // SMOOTH SCROLL
        // ============================================
        initSmoothScroll: function () {
            $('a[href^="#"]').on('click', function (e) {
                const target = $(this.getAttribute('href'));
                if (target.length) {
                    e.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 70
                    }, 800);
                }
            });
        },

        // ============================================
        // ALERT AUTO CLOSE
        // ============================================
        initAlertAutoClose: function () {
            $('.alert:not(.alert-permanent)').each(function () {
                const $alert = $(this);
                setTimeout(() => {
                    $alert.fadeOut(500, function () {
                        $(this).remove();
                    });
                }, 5000);
            });
        },

        // ============================================
        // TABLE ACTIONS
        // ============================================
        initTableActions: function () {
            // Delete row
            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();
                const $row = $(this).closest('tr');

                if (confirm('Are you sure you want to delete this item?')) {
                    $row.fadeOut(300, function () {
                        $(this).remove();
                        AdminLTEDashboard.showToast('Item deleted successfully', 'success');
                    });
                }
            });

            // Edit row
            $(document).on('click', '.btn-edit', function (e) {
                e.preventDefault();
                AdminLTEDashboard.showToast('Edit functionality coming soon', 'info');
            });

            // View details
            $(document).on('click', '.btn-view', function (e) {
                e.preventDefault();
                AdminLTEDashboard.showToast('View details coming soon', 'info');
            });
        },

        // ============================================
        // QUICK ACTIONS
        // ============================================
        initQuickActions: function () {
            // Animations disabled
        },

        // ============================================
        // LOADING STATES
        // ============================================
        initLoadingStates: function () {
            // Add loading state to buttons on click
            // Loading state logic removed to prevent form submission issues
            // $(document).on('click', '.btn[type="submit"]', function() { ... });
        },

        // ============================================
        // TOAST NOTIFICATIONS
        // ============================================
        showToast: function (message, type = 'info') {
            const bgColors = {
                'success': '#10b981',
                'error': '#ef4444',
                'warning': '#f59e0b',
                'info': '#06b6d4'
            };

            const icons = {
                'success': 'bi-check-circle-fill',
                'error': 'bi-exclamation-triangle-fill',
                'warning': 'bi-exclamation-circle-fill',
                'info': 'bi-info-circle-fill'
            };

            const toast = $(`
                <div class="toast-notification" style="
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: ${bgColors[type]};
                    color: white;
                    padding: 15px 20px;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    z-index: 9999;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    min-width: 250px;
                    animation: slideInRight 0.3s ease;
                ">
                    <i class="bi ${icons[type]}" style="font-size: 1.2rem;"></i>
                    <span>${message}</span>
                </div>
            `);

            $('body').append(toast);

            setTimeout(() => {
                toast.fadeOut(300, function () {
                    $(this).remove();
                });
            }, 3000);
        },

        // ============================================
        // CONFIRM DIALOG
        // ============================================
        confirmDialog: function (message, callback) {
            if (confirm(message)) {
                callback();
            }
        },

        // ============================================
        // LOADING OVERLAY
        // ============================================
        showLoading: function () {
            const overlay = $(`
                <div class="loading-overlay" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,0.5);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 9999;
                ">
                    <div style="
                        background: white;
                        padding: 30px;
                        border-radius: 10px;
                        text-align: center;
                    ">
                        <div class="loading-spinner" style="
                            width: 40px;
                            height: 40px;
                            border: 4px solid rgba(99, 102, 241, 0.3);
                            border-top-color: #6366f1;
                            margin: 0 auto 15px;
                        "></div>
                        <p style="margin: 0; color: #1e293b;">Loading...</p>
                    </div>
                </div>
            `);
            $('body').append(overlay);
        },

        hideLoading: function () {
            $('.loading-overlay').fadeOut(300, function () {
                $(this).remove();
            });
        },

        // ============================================
        // REFRESH DATA
        // ============================================
        refreshData: function (url, callback) {
            AdminLTEDashboard.showLoading();

            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    AdminLTEDashboard.hideLoading();
                    if (callback) callback(response);
                    AdminLTEDashboard.showToast('Data refreshed successfully', 'success');
                },
                error: function () {
                    AdminLTEDashboard.hideLoading();
                    AdminLTEDashboard.showToast('Failed to refresh data', 'error');
                }
            });
        }
    };

    // ============================================
    // INITIALIZE ON DOCUMENT READY
    // ============================================
    $(document).ready(function () {
        AdminLTEDashboard.init();
    });

    // ============================================
    // EXPOSE TO GLOBAL SCOPE
    // ============================================
    window.AdminLTEDashboard = AdminLTEDashboard;

})(jQuery);

// ============================================
// CSS ANIMATIONS
// ============================================
// CSS Animations disabled
// const style = document.createElement('style'); ...
