<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Security Sanitize Helper
 * Common sanitization and validation functions for input data
 */

if (!function_exists('sanitize_string')) {
    /**
     * Sanitize a string input - removes HTML tags and trims whitespace
     * @param string $str Input string
     * @return string Sanitized string
     */
    function sanitize_string($str) {
        if (is_null($str)) return '';
        return trim(strip_tags($str));
    }
}

if (!function_exists('sanitize_html')) {
    /**
     * Sanitize HTML content - allows safe HTML tags
     * @param string $str Input string
     * @return string Sanitized HTML
     */
    function sanitize_html($str) {
        if (is_null($str)) return '';
        $allowed_tags = '<p><br><strong><em><ul><ol><li><a><h1><h2><h3><h4><h5><h6>';
        return trim(strip_tags($str, $allowed_tags));
    }
}

if (!function_exists('sanitize_filename')) {
    /**
     * Sanitize filename - removes dangerous characters
     * @param string $filename Input filename
     * @return string Sanitized filename
     */
    function sanitize_filename($filename) {
        // Remove any characters that aren't alphanumeric, dash, underscore, or dot
        $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $filename);
        // Remove multiple dots
        $filename = preg_replace('/\.{2,}/', '.', $filename);
        return $filename;
    }
}

if (!function_exists('sanitize_integer')) {
    /**
     * Sanitize integer input
     * @param mixed $value Input value
     * @param int $default Default value if invalid
     * @return int Sanitized integer
     */
    function sanitize_integer($value, $default = 0) {
        $value = filter_var($value, FILTER_VALIDATE_INT);
        return ($value !== false) ? $value : $default;
    }
}

if (!function_exists('sanitize_float')) {
    /**
     * Sanitize float/decimal input
     * @param mixed $value Input value
     * @param float $default Default value if invalid
     * @return float Sanitized float
     */
    function sanitize_float($value, $default = 0.0) {
        $value = filter_var($value, FILTER_VALIDATE_FLOAT);
        return ($value !== false) ? $value : $default;
    }
}

if (!function_exists('sanitize_email')) {
    /**
     * Sanitize email input
     * @param string $email Input email
     * @return string|false Sanitized email or false if invalid
     */
    function sanitize_email($email) {
        $email = trim(strtolower($email));
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : false;
    }
}

if (!function_exists('validate_date')) {
    /**
     * Validate date format
     * @param string $date Input date
     * @param string $format Expected format
     * @return bool True if valid
     */
    function validate_date($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}

if (!function_exists('validate_password_strength')) {
    /**
     * Validate password strength
     * @param string $password Input password
     * @return array Array with 'valid' boolean and 'errors' array
     */
    function validate_password_strength($password) {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        }
        if (strlen($password) > 72) {
            $errors[] = 'Password must be less than 72 characters';
        }
        if (!preg_match('/[A-Za-z]/', $password)) {
            $errors[] = 'Password must contain at least one letter';
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain at least one number';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}

if (!function_exists('escape_output')) {
    /**
     * Escape output for HTML display
     * @param string $str Input string
     * @return string Escaped string
     */
    function escape_output($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('is_valid_course_code')) {
    /**
     * Validate course code format
     * @param string $code Course code
     * @return bool True if valid
     */
    function is_valid_course_code($code) {
        // Allow alphanumeric and hyphens, 3-20 characters
        return preg_match('/^[A-Za-z0-9\-]{3,20}$/', $code);
    }
}
