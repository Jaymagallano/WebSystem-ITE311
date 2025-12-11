<?php
/**
 * Security Testing Suite for Course Enrollment System
 * 
 * This script tests for various security vulnerabilities:
 * 1. Authorization Bypass
 * 2. SQL Injection
 * 3. CSRF Protection
 * 4. Data Tampering
 * 5. Input Validation
 */

class SecurityTester {
    private $base_url;
    private $session_cookie;
    
    public function __construct($base_url = 'http://localhost/ITE311-MAGALLANO/') {
        $this->base_url = rtrim($base_url, '/') . '/';
    }
    
    /**
     * Test 1: Authorization Bypass
     * Attempt to access enrollment endpoint without authentication
     */
    public function testAuthorizationBypass() {
        echo "\n=== TESTING AUTHORIZATION BYPASS ===\n";
        
        // Test direct access to enrollment endpoint without login
        $test_cases = [
            ['method' => 'GET', 'url' => 'student/enroll/1'],
            ['method' => 'POST', 'url' => 'student/enroll/1'],
            ['method' => 'GET', 'url' => 'student/courses'],
            ['method' => 'POST', 'url' => 'course/enroll', 'data' => ['course_id' => 1]]
        ];
        
        foreach ($test_cases as $test) {
            echo "Testing {$test['method']} {$test['url']}...\n";
            $response = $this->makeRequest($test['method'], $test['url'], $test['data'] ?? []);
            
            if (strpos($response, 'login') !== false || strpos($response, 'unauthorized') !== false) {
                echo "✓ PASS: Properly redirected to login\n";
            } else {
                echo "✗ FAIL: Authorization bypass detected!\n";
            }
        }
    }
    
    /**
     * Test 2: SQL Injection
     * Test for SQL injection vulnerabilities in course_id parameter
     */
    public function testSQLInjection() {
        echo "\n=== TESTING SQL INJECTION ===\n";
        
        $sql_payloads = [
            "1 OR 1=1",
            "1' OR '1'='1",
            "1; DROP TABLE enrollments; --",
            "1 UNION SELECT * FROM users --",
            "1' AND (SELECT COUNT(*) FROM users) > 0 --"
        ];
        
        foreach ($sql_payloads as $payload) {
            echo "Testing payload: " . htmlspecialchars($payload) . "\n";
            
            // Test in enrollment endpoint
            $response = $this->makeRequest('GET', "student/enroll/" . urlencode($payload));
            
            if (strpos($response, 'error') !== false || strpos($response, 'SQL') !== false) {
                echo "✗ POTENTIAL SQL INJECTION VULNERABILITY\n";
            } else {
                echo "✓ PASS: Payload handled safely\n";
            }
        }
    }
    
    /**
     * Test 3: CSRF Protection
     * Check if CSRF tokens are implemented and validated
     */
    public function testCSRFProtection() {
        echo "\n=== TESTING CSRF PROTECTION ===\n";
        
        // Check if CSRF is enabled in config
        $config_path = dirname(__FILE__) . '/application/config/config.php';
        if (file_exists($config_path)) {
            $config_content = file_get_contents($config_path);
            if (strpos($config_content, "'csrf_protection' = TRUE") !== false) {
                echo "✓ CSRF protection is enabled in config\n";
            } else {
                echo "✗ CSRF protection is DISABLED in config\n";
            }
        }
        
        // Test enrollment without CSRF token
        $response = $this->makeRequest('POST', 'student/enroll/1', ['course_id' => 1]);
        
        if (strpos($response, 'csrf') !== false || strpos($response, 'token') !== false) {
            echo "✓ PASS: CSRF protection active\n";
        } else {
            echo "✗ FAIL: No CSRF protection detected\n";
        }
    }
    
    /**
     * Test 4: Data Tampering
     * Test if user can enroll other users by manipulating user_id
     */
    public function testDataTampering() {
        echo "\n=== TESTING DATA TAMPERING ===\n";
        
        // Simulate attempts to enroll other users
        $tampered_requests = [
            ['user_id' => 999, 'course_id' => 1],
            ['student_id' => 999, 'course_id' => 1],
            ['user_id' => 'admin', 'course_id' => 1]
        ];
        
        foreach ($tampered_requests as $data) {
            echo "Testing data tampering with: " . json_encode($data) . "\n";
            $response = $this->makeRequest('POST', 'student/enroll/1', $data);
            
            // Check if the system uses session data instead of client data
            if (strpos($response, 'unauthorized') !== false || strpos($response, 'access denied') !== false) {
                echo "✓ PASS: Data tampering prevented\n";
            } else {
                echo "✗ FAIL: Potential data tampering vulnerability\n";
            }
        }
    }
    
    /**
     * Test 5: Input Validation
     * Test enrollment with invalid course IDs
     */
    public function testInputValidation() {
        echo "\n=== TESTING INPUT VALIDATION ===\n";
        
        $invalid_inputs = [
            'abc',           // Non-numeric
            '-1',            // Negative number
            '0',             // Zero
            '999999',        // Non-existent course
            '',              // Empty string
            'null',          // Null string
            '<script>alert(1)</script>' // XSS attempt
        ];
        
        foreach ($invalid_inputs as $input) {
            echo "Testing invalid input: " . htmlspecialchars($input) . "\n";
            $response = $this->makeRequest('GET', "student/enroll/" . urlencode($input));
            
            if (strpos($response, 'error') !== false || strpos($response, 'not found') !== false) {
                echo "✓ PASS: Invalid input properly handled\n";
            } else {
                echo "✗ FAIL: Invalid input not properly validated\n";
            }
        }
    }
    
    /**
     * Make HTTP request using cURL
     */
    private function makeRequest($method, $endpoint, $data = []) {
        $url = $this->base_url . $endpoint;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            }
        }
        
        if ($this->session_cookie) {
            curl_setopt($ch, CURLOPT_COOKIE, $this->session_cookie);
        }
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $response;
    }
    
    /**
     * Run all security tests
     */
    public function runAllTests() {
        echo "Starting Security Tests for Course Enrollment System\n";
        echo "====================================================\n";
        
        $this->testAuthorizationBypass();
        $this->testSQLInjection();
        $this->testCSRFProtection();
        $this->testDataTampering();
        $this->testInputValidation();
        
        echo "\n=== SECURITY TEST SUMMARY ===\n";
        echo "Tests completed. Review the results above.\n";
        echo "Address any FAIL items to improve security.\n";
    }
}

// Run the tests
if (php_sapi_name() === 'cli') {
    $tester = new SecurityTester();
    $tester->runAllTests();
} else {
    echo "This script should be run from command line for security reasons.";
}
?>