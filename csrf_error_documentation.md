# CSRF Token Mismatch Error Documentation

## Error Message
```
An Error Was Encountered
The action you have requested is not allowed (CSRF token mismatch).
```

## What is CSRF?
**Cross-Site Request Forgery (CSRF)** is a security vulnerability where malicious websites can perform unauthorized actions on behalf of authenticated users.

## Why This Error Occurs

### 1. **CSRF Protection Enabled**
When CSRF protection is enabled in CodeIgniter, all state-changing operations (POST, PUT, DELETE) require a valid CSRF token.

### 2. **Missing or Invalid Token**
The error occurs when:
- No CSRF token is provided in the request
- The CSRF token doesn't match the server-generated token
- The CSRF token has expired
- The token is malformed or corrupted

## Common Scenarios

### Scenario 1: Direct URL Access
```
http://localhost/ITE311-MAGALLANO/student/enroll/2
```
**Result:** CSRF token mismatch error

### Scenario 2: Form Submission Without Token
```html
<form method="POST" action="/student/enroll/1">
    <input type="hidden" name="course_id" value="1">
    <button type="submit">Enroll</button>
</form>
```
**Result:** CSRF token mismatch error

### Scenario 3: AJAX Request Without Token
```javascript
fetch('/student/enroll/1', {
    method: 'POST',
    body: 'course_id=1'
})
```
**Result:** CSRF token mismatch error

## How CSRF Protection Works

### 1. **Token Generation**
```php
// CodeIgniter generates a unique token for each session
$csrf_token = $this->security->get_csrf_hash();
```

### 2. **Token Validation**
```php
// Server validates the token on each request
if (!$this->security->verify_csrf_token()) {
    show_error('CSRF token mismatch', 403);
}
```

### 3. **Token Inclusion**
```html
<!-- Token must be included in forms -->
<input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
```

## Configuration Settings

### Enable/Disable CSRF Protection
```php
// In application/config/config.php
$config['csrf_protection'] = TRUE;  // Enable
$config['csrf_protection'] = FALSE; // Disable
```

### CSRF Configuration Options
```php
$config['csrf_token_name'] = 'csrf_token';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200; // 2 hours
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array('api/*');
```

## Solutions

### Solution 1: Disable CSRF Protection (Development Only)
```php
// In config.php
$config['csrf_protection'] = FALSE;
```

### Solution 2: Add CSRF Token to Forms
```html
<form method="POST" action="/student/enroll/1">
    <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
    <input type="hidden" name="course_id" value="1">
    <button type="submit">Enroll</button>
</form>
```

### Solution 3: Include Token in AJAX Requests
```javascript
// Get CSRF token from meta tag or cookie
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

fetch('/student/enroll/1', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-TOKEN': csrfToken
    },
    body: `course_id=1&csrf_token=${csrfToken}`
})
```

### Solution 4: Exclude Specific URIs
```php
// In config.php - exclude certain endpoints from CSRF
$config['csrf_exclude_uris'] = array(
    'api/*',
    'webhook/*',
    'student/enroll/*'
);
```

## Security Testing Context

### Testing CSRF Protection
```javascript
// Test 1: Request without CSRF token (should fail)
fetch('/student/enroll/1', {
    method: 'POST',
    body: 'course_id=1'
})
.then(response => {
    console.log('Status:', response.status); // Expected: 403
});

// Test 2: Request with valid CSRF token (should succeed)
fetch('/student/enroll/1', {
    method: 'POST',
    body: `course_id=1&csrf_token=${validToken}`
})
.then(response => {
    console.log('Status:', response.status); // Expected: 200
});
```

### Expected Security Behavior
- ✅ **Secure**: Requests without valid CSRF tokens are rejected
- ❌ **Vulnerable**: All requests are accepted regardless of CSRF token

## Implementation in Student Controller

### With CSRF Protection (Secure)
```php
public function enroll($course_id) {
    // CSRF Protection Check
    $csrf_token = $this->input->post($this->config->item('csrf_token_name')) ?: 
                  $this->input->get($this->config->item('csrf_token_name'));
    $csrf_cookie = $this->input->cookie($this->config->item('csrf_cookie_name'));
    
    if (!$csrf_token || !$csrf_cookie || !hash_equals($csrf_cookie, $csrf_token)) {
        show_error('The action you have requested is not allowed (CSRF token mismatch).', 403);
    }
    
    // Process enrollment...
}
```

### Without CSRF Protection (Development)
```php
public function enroll($course_id) {
    // CSRF Protection disabled for normal usage
    // Uncomment below lines to re-enable CSRF protection:
    /*
    $csrf_token = $this->input->post($this->config->item('csrf_token_name')) ?: 
                  $this->input->get($this->config->item('csrf_token_name'));
    $csrf_cookie = $this->input->cookie($this->config->item('csrf_cookie_name'));
    
    if (!$csrf_token || !$csrf_cookie || !hash_equals($csrf_cookie, $csrf_token)) {
        show_error('The action you have requested is not allowed (CSRF token mismatch).', 403);
    }
    */
    
    // Process enrollment...
}
```

## Best Practices

### 1. **Production Environment**
- Always enable CSRF protection in production
- Use HTTPS to prevent token interception
- Set appropriate token expiration times

### 2. **Development Environment**
- Disable CSRF for easier testing
- Re-enable for security testing
- Document CSRF implementation

### 3. **Form Implementation**
```php
// In views, always include CSRF token
<?php echo form_open('student/enroll/1'); ?>
    <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
    <input type="hidden" name="course_id" value="1">
    <button type="submit">Enroll</button>
<?php echo form_close(); ?>
```

### 4. **AJAX Implementation**
```javascript
// Set CSRF token in meta tag
<meta name="csrf-token" content="<?= $this->security->get_csrf_hash() ?>">

// Use in AJAX requests
$.ajaxSetup({
    beforeSend: function(xhr, settings) {
        if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type) && !this.crossDomain) {
            xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name=csrf-token]').attr('content'));
        }
    }
});
```

## Troubleshooting

### Common Issues
1. **Token Expiration**: Tokens expire after configured time
2. **Session Issues**: CSRF tokens are tied to user sessions
3. **Multiple Tabs**: Token regeneration can cause issues across tabs
4. **AJAX Calls**: Missing token in AJAX requests

### Debug Steps
1. Check if CSRF is enabled in config
2. Verify token is included in request
3. Check token expiration settings
4. Validate session is active
5. Ensure proper token name configuration

## Security Impact

### With CSRF Protection
- ✅ Prevents unauthorized actions
- ✅ Protects against malicious websites
- ✅ Ensures request authenticity
- ✅ Maintains user security

### Without CSRF Protection
- ❌ Vulnerable to CSRF attacks
- ❌ Malicious sites can perform actions
- ❌ No request authenticity verification
- ❌ Security risk for users

## Conclusion

CSRF protection is a critical security feature that prevents unauthorized actions on behalf of authenticated users. While it may cause inconvenience during development, it's essential for production security. Always implement proper CSRF token handling in forms and AJAX requests when CSRF protection is enabled.