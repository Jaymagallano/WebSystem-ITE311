# Security Testing Guide for Course Enrollment System

## Overview
This guide provides comprehensive security testing procedures for the course enrollment system, complementing the automated Postman security tests.

## Prerequisites
- XAMPP server running
- Course enrollment system deployed
- Postman installed
- Basic understanding of web security concepts

## Security Test Categories

### 1. Authorization Bypass Tests

#### Test Objectives
- Verify proper authentication enforcement
- Ensure unauthorized users cannot access protected resources

#### Manual Testing Steps
1. **Direct URL Access Test**
   - Navigate to `http://localhost/ITE311-MAGALLANO/student/enroll/1` without logging in
   - Expected: Redirect to login page or show access denied message

2. **Session Manipulation Test**
   - Log in as a student
   - Clear session cookies
   - Try to access enrollment pages
   - Expected: Authentication required

#### Automated Tests
Use the "Authorization Bypass Tests" collection in Postman to verify:
- GET requests without authentication
- POST requests without valid session

### 2. SQL Injection Tests

#### Test Objectives
- Identify SQL injection vulnerabilities
- Verify input sanitization

#### Common Injection Patterns
- `1 OR 1=1` - Boolean-based injection
- `1 UNION SELECT * FROM users` - Union-based injection
- `1; DROP TABLE enrollments; --` - Destructive injection

#### Manual Testing
1. **URL Parameter Injection**
   - Modify course ID in URL: `/student/enroll/1' OR '1'='1`
   - Check for database errors or unexpected behavior

2. **Form Field Injection**
   - Enter SQL payloads in form fields
   - Monitor for error messages revealing database structure

#### Automated Tests
Run the "SQL Injection Tests" collection to test various injection vectors.

### 3. Cross-Site Request Forgery (CSRF) Tests

#### Test Objectives
- Verify CSRF token implementation
- Ensure state-changing operations are protected

#### Manual Testing
1. **Missing CSRF Token Test**
   - Submit enrollment form without CSRF token
   - Expected: Request should be rejected

2. **Invalid CSRF Token Test**
   - Modify CSRF token value before submission
   - Expected: Request should be rejected

#### Automated Tests
Use the "CSRF Tests" collection to verify token validation.

### 4. Data Tampering Tests

#### Test Objectives
- Verify user cannot manipulate other users' data
- Ensure proper authorization checks

#### Manual Testing
1. **User ID Manipulation**
   - Intercept enrollment request
   - Change user_id parameter to another user's ID
   - Expected: Operation should fail or be rejected

2. **Course ID Manipulation**
   - Try to enroll in courses not available to the user
   - Expected: Proper authorization check

### 5. Input Validation Tests

#### Test Objectives
- Verify proper input sanitization
- Test boundary conditions

#### Test Cases
1. **Invalid Data Types**
   - String values for numeric fields
   - Negative numbers where positive expected
   - Extremely large values

2. **XSS Payloads**
   - `<script>alert(1)</script>`
   - `javascript:alert(1)`
   - `<img src=x onerror=alert(1)>`

3. **Special Characters**
   - Null bytes (`%00`)
   - Unicode characters
   - Path traversal sequences (`../`)

## Security Testing Checklist

### Authentication & Authorization
- [ ] Login required for protected pages
- [ ] Session timeout implemented
- [ ] Password complexity enforced
- [ ] Account lockout after failed attempts
- [ ] Role-based access control working

### Input Validation
- [ ] SQL injection protection
- [ ] XSS prevention
- [ ] File upload restrictions
- [ ] Parameter tampering protection
- [ ] Input length limits enforced

### Session Management
- [ ] Secure session cookies
- [ ] Session regeneration after login
- [ ] Proper session termination
- [ ] CSRF token implementation
- [ ] Session fixation protection

### Data Protection
- [ ] Sensitive data encrypted
- [ ] Database credentials secured
- [ ] Error messages don't reveal sensitive info
- [ ] Audit logging implemented
- [ ] Data backup and recovery procedures

## Running the Tests

### Setup
1. Import `postman_security_tests.json` into Postman
2. Set environment variable `base_url` to `http://localhost/ITE311-MAGALLANO`
3. Ensure test database has sample data

### Execution
1. Run individual test folders for specific vulnerability types
2. Run entire collection for comprehensive testing
3. Review test results and investigate failures
4. Document findings and remediation steps

### Expected Results
- Authorization tests should show proper access control
- SQL injection tests should not reveal database errors
- CSRF tests should reject requests without valid tokens
- Input validation should handle malicious input gracefully

## Remediation Guidelines

### SQL Injection Prevention
```php
// Use prepared statements
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
```

### XSS Prevention
```php
// Escape output
echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');
```

### CSRF Protection
```php
// Generate and validate CSRF tokens
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('CSRF token mismatch');
}
```

### Authorization Checks
```php
// Verify user permissions
if (!isLoggedIn() || !hasPermission('enroll_courses')) {
    redirect('/login');
}
```

## Reporting Security Issues

### Documentation Requirements
1. **Vulnerability Description**
   - Type of vulnerability
   - Affected components
   - Risk level assessment

2. **Reproduction Steps**
   - Detailed steps to reproduce
   - Screenshots or video proof
   - Test environment details

3. **Impact Assessment**
   - Potential damage
   - Affected users/data
   - Business impact

4. **Remediation Recommendations**
   - Immediate fixes
   - Long-term improvements
   - Prevention strategies

## Security Testing Schedule

### Regular Testing
- **Weekly**: Automated security scans
- **Monthly**: Manual penetration testing
- **Quarterly**: Comprehensive security review
- **After Changes**: Security regression testing

### Continuous Monitoring
- Monitor application logs for suspicious activity
- Set up alerts for failed authentication attempts
- Regular security patch updates
- Code review for security issues

## Tools and Resources

### Testing Tools
- **Postman**: API security testing
- **OWASP ZAP**: Web application security scanner
- **Burp Suite**: Web vulnerability scanner
- **SQLMap**: SQL injection testing tool

### Security Resources
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [OWASP Testing Guide](https://owasp.org/www-project-web-security-testing-guide/)
- [PHP Security Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/PHP_Configuration_Cheat_Sheet.html)

## Conclusion

Regular security testing is essential for maintaining a secure course enrollment system. This guide provides a structured approach to identifying and addressing security vulnerabilities. Always follow responsible disclosure practices when reporting security issues.