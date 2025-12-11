<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Security Configuration
 * 
 * Enhanced security settings for the application
 * Copy these settings to your main config.php file
 */

// Enable CSRF Protection
$config['csrf_protection'] = TRUE;
$config['csrf_token_name'] = 'csrf_token';
$config['csrf_cookie_name'] = 'csrf_cookie';
$config['csrf_expire'] = 7200; // 2 hours
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array('api/*'); // Exclude API endpoints if needed

// Enhanced Session Security
$config['sess_cookie_name'] = 'secure_session';
$config['sess_samesite'] = 'Strict';
$config['sess_expiration'] = 3600; // 1 hour
$config['sess_save_path'] = NULL;
$config['sess_match_ip'] = TRUE; // Match IP address
$config['sess_time_to_update'] = 300; // 5 minutes
$config['sess_regenerate_destroy'] = TRUE;

// Enhanced Cookie Security
$config['cookie_secure'] = FALSE; // Set to TRUE in production with HTTPS
$config['cookie_httponly'] = TRUE; // Prevent XSS attacks
$config['cookie_samesite'] = 'Strict';

// Input Security
$config['global_xss_filtering'] = TRUE; // Enable XSS filtering
$config['standardize_newlines'] = TRUE;

// Allowed URI Characters (restrictive)
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

// Enable Error Logging
$config['log_threshold'] = 1; // Log errors only
$config['log_path'] = APPPATH . 'logs/';

// Security Headers (implement in .htaccess or web server config)
/*
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
Header always set Content-Security-Policy "default-src 'self'"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
*/

// Database Security Settings
$config['db_debug'] = FALSE; // Disable in production
$config['db_strict_mode'] = TRUE;

// File Upload Security
$config['upload_max_size'] = 5120; // 5MB
$config['upload_allowed_types'] = 'pdf|doc|docx|txt|jpg|jpeg|png';
$config['upload_encrypt_name'] = TRUE;
$config['upload_remove_spaces'] = TRUE;

// Rate Limiting Settings
$config['rate_limit_enabled'] = TRUE;
$config['rate_limit_requests'] = 100; // requests per hour
$config['rate_limit_window'] = 3600; // 1 hour in seconds

// Password Security
$config['password_min_length'] = 8;
$config['password_require_uppercase'] = TRUE;
$config['password_require_lowercase'] = TRUE;
$config['password_require_numbers'] = TRUE;
$config['password_require_symbols'] = TRUE;

// Account Security
$config['max_login_attempts'] = 5;
$config['lockout_duration'] = 900; // 15 minutes
$config['password_reset_expiry'] = 3600; // 1 hour

// Audit Logging
$config['audit_log_enabled'] = TRUE;
$config['audit_log_actions'] = array(
    'login', 'logout', 'enrollment', 'grade_change', 'file_upload'
);
?>