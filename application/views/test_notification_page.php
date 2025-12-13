<?php
// This is a simple test page to check if notifications work
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Notification Test Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <script>
        window.base_url = '<?= base_url() ?>';
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1>Notification System Test</h1>
        <p>This page tests the notification system functionality.</p>
        
        <div class="card">
            <div class="card-header">
                <h5>Notification Test Controls</h5>
            </div>
            <div class="card-body">
                <button id="testLoad" class="btn btn-primary me-2">Load Notifications</button>
                <button id="testMarkRead" class="btn btn-success me-2">Mark Notification #1 as Read</button>
                <button id="testMarkAll" class="btn btn-warning">Mark All as Read</button>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5>Results</h5>
            </div>
            <div class="card-body">
                <pre id="results">Click buttons above to test notifications</pre>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('assets/js/notifications.js') ?>"></script>
    
    <script>
        $(document).ready(function() {
            $('#testLoad').click(function() {
                $('#results').text('Loading notifications...');
                
                const baseUrl = typeof window.base_url !== 'undefined' ? window.base_url : 
                               (window.location.origin + '/ITE311-MAGALLANO/');
                
                console.log('Testing with base URL:', baseUrl);
                
                $.ajax({
                    url: baseUrl + 'notifications/get_unread',
                    method: 'GET',
                    dataType: 'json',
                    xhrFields: {
                        withCredentials: true
                    },
                    success: function(response) {
                        $('#results').text('Success: ' + JSON.stringify(response, null, 2));
                    },
                    error: function(xhr, status, error) {
                        $('#results').text('Error: ' + error + '\nStatus: ' + status + '\nResponse: ' + xhr.responseText);
                        console.error('AJAX Error:', xhr, status, error);
                    }
                });
            });
            
            $('#testMarkRead').click(function() {
                $('#results').text('Marking notification #1 as read...');
                
                const baseUrl = typeof window.base_url !== 'undefined' ? window.base_url : 
                               (window.location.origin + '/ITE311-MAGALLANO/');
                
                $.ajax({
                    url: baseUrl + 'notifications/mark_read/1',
                    method: 'GET',
                    dataType: 'json',
                    xhrFields: {
                        withCredentials: true
                    },
                    success: function(response) {
                        $('#results').text('Mark as read success: ' + JSON.stringify(response, null, 2));
                    },
                    error: function(xhr, status, error) {
                        $('#results').text('Error: ' + error + '\nStatus: ' + status + '\nResponse: ' + xhr.responseText);
                        console.error('AJAX Error:', xhr, status, error);
                    }
                });
            });
            
            $('#testMarkAll').click(function() {
                $('#results').text('Marking all notifications as read...');
                
                const baseUrl = typeof window.base_url !== 'undefined' ? window.base_url : 
                               (window.location.origin + '/ITE311-MAGALLANO/');
                
                $.ajax({
                    url: baseUrl + 'notifications/mark_all_read',
                    method: 'GET',
                    dataType: 'json',
                    xhrFields: {
                        withCredentials: true
                    },
                    success: function(response) {
                        $('#results').text('Mark all as read success: ' + JSON.stringify(response, null, 2));
                    },
                    error: function(xhr, status, error) {
                        $('#results').text('Error: ' + error + '\nStatus: ' + status + '\nResponse: ' + xhr.responseText);
                        console.error('AJAX Error:', xhr, status, error);
                    }
                });
            });
        });
    </script>
</body>
</html>