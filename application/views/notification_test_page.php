<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification System Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <script>
        window.base_url = '<?= base_url() ?>';
    </script>
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .test-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .notification-demo {
            position: relative;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1 class="mb-4"><i class="bi bi-bell"></i> Notification System Test</h1>
        
        <div class="alert alert-info">
            <h4>Testing Instructions</h4>
            <p>This page tests the notification system functionality:</p>
            <ol>
                <li>Click the notification bell icon below</li>
                <li>Verify that the dropdown appears with notifications</li>
                <li>Check that the badge count matches the number of notifications</li>
                <li>Try clicking on a notification to see if it redirects correctly</li>
            </ol>
        </div>
        
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Notification Bell Demo</h5>
                <p>Click the bell icon to test the notification dropdown:</p>
                
                <div class="position-relative" id="notification-container">
                    <span class="notification-bell" id="notification-bell" style="font-size: 2rem; cursor: pointer; color: #0d6efd;">
                        <i class="bi bi-bell-fill"></i>
                        <span class="notification-badge" id="notification-count" style="position: absolute; top: -8px; right: -8px; background: #dc3545; color: white; font-size: 0.7rem; font-weight: bold; min-width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; padding: 2px;">0</span>
                    </span>
                    
                    <!-- Notification Dropdown -->
                    <div class="notification-dropdown" id="notification-dropdown" style="position: absolute; top: 100%; right: 0; width: 380px; max-height: 500px; background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); margin-top: 10px; display: none; z-index: 9999; overflow: hidden;">
                        <div style="padding: 15px 20px; background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%); color: white; font-weight: 600; display: flex; justify-content: space-between; align-items: center;">
                            <span>Notifications</span>
                            <a href="javascript:void(0)" id="mark-all-read" class="text-white text-decoration-none" style="font-size: 0.85rem;">
                                <i class="bi bi-check2-all"></i> Mark all read
                            </a>
                        </div>
                        <div class="notification-list" id="notification-list" style="max-height: 400px; overflow-y: auto;">
                            <div style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                <i class="bi bi-bell-slash" style="font-size: 3rem; margin-bottom: 10px; display: block;"></i>
                                <p>Loading notifications...</p>
                            </div>
                        </div>
                        <div style="padding: 12px 20px; background: #f8f9fa; text-align: center;">
                            <a href="#" id="view-all-notifications" style="color: #0d6efd; text-decoration: none; font-weight: 500; font-size: 0.9rem;">View All Notifications</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Debug Information</h5>
                <div id="debug-info">
                    <p>Status: <span id="status" class="badge bg-secondary">Initializing...</span></p>
                    <p>Base URL: <code id="base-url-display"></code></p>
                    <div id="logs"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('assets/js/notifications.js') ?>"></script>
    <script>
        $(document).ready(function() {
            // Display base URL for debugging
            $('#base-url-display').text(window.base_url || 'Not set');
            
            // Update status
            $('#status').removeClass('bg-secondary').addClass('bg-success').text('Ready');
            
            // Log initialization
            console.log('Test page initialized');
            $('#logs').append('<p class="text-muted"><small>[' + new Date().toLocaleTimeString() + '] Test page initialized</small></p>');
            
            // Test the notification system manually
            $('#notification-bell').on('click', function(e) {
                e.stopPropagation();
                console.log('Bell clicked');
                $('#logs').append('<p class="text-muted"><small>[' + new Date().toLocaleTimeString() + '] Bell clicked</small></p>');
            });
        });
    </script>
</body>
</html>