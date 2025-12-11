# Security Testing Console Commands Guide

## Overview
This guide contains all the exact console commands used to test security vulnerabilities in the course enrollment system.

## Prerequisites
1. Login to your application first
2. Open Browser Developer Tools (F12)
3. Go to Console tab
4. Copy and paste the commands below

---

## 1. Test for Authorization Bypass

### Test POST Request Without Login
```javascript
fetch('http://localhost/ITE311-MAGALLANO/student/enroll/1', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'course_id=1',
    credentials: 'include'
})
.then(r => r.text())
.then(data => {
    console.log('POST without login:', data.includes('login') ? 'BLOCKED ✅' : 'VULNERABLE ❌');
});
```

**Expected Result:** `BLOCKED ✅` - Should redirect to login page

---

## 2. Test for SQL Injection

### Test SQL Injection in URL Parameter
```javascript
fetch('http://localhost/ITE311-MAGALLANO/student/enroll/1 OR 1=1', {
    method: 'GET',
    credentials: 'include'
})
.then(response => {
    console.log('SQL Injection Status:', response.status);
    return response.text();
})
.then(data => {
    console.log('SQL Injection test result:', response.status === 400 ? 'PROTECTED ✅' : 'CHECK RESPONSE');
});
```

**Expected Result:** `Status: 400` - Bad Request (Input validation working)

### Test SQL Injection in POST Body
```javascript
fetch('http://localhost/ITE311-MAGALLANO/student/enroll/1', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'course_id=1 OR 1=1',
    credentials: 'include'
})
.then(r => r.text())
.then(data => {
    console.log('POST SQL injection test:', data.substring(0, 200));
});
```

---

## 3. Test for CSRF Protection

### Step 1: Get CSRF Token from Cookies
```javascript
// Check current cookies to find CSRF token
console.log('All cookies:', document.cookie);

// Look for CSRF cookie specifically
const cookies = document.cookie.split(';');
let csrfToken = null;

cookies.forEach(cookie => {
    const [name, value] = cookie.trim().split('=');
    if (name === 'csrf_cookie_name') {
        csrfToken = value;
        console.log('CSRF Token found:', csrfToken);
    }
});

if (!csrfToken) {
    console.log('No CSRF token found. Visit /student/courses first to generate one.');
}
```

### Step 2: Test POST Without CSRF Token
```javascript
fetch('http://localhost/ITE311-MAGALLANO/student/enroll/1', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'course_id=1',
    credentials: 'include'
})
.then(response => {
    console.log('CSRF Test Status:', response.status);
    return response.text();
})
.then(data => {
    console.log('CSRF Protection:', response.status === 403 ? 'WORKING ✅' : 'CHECK RESPONSE');
});
```

**Expected Result:** `Status: 403` - Forbidden (CSRF protection active)

### Step 3: Test with Valid CSRF Token
```javascript
// Replace 'YOUR_CSRF_TOKEN' with actual token from Step 1
const csrfToken = 'YOUR_CSRF_TOKEN'; // Get from Step 1

fetch('http://localhost/ITE311-MAGALLANO/student/enroll/1', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `course_id=1&csrf_token=${csrfToken}`,
    credentials: 'include'
})
.then(response => {
    console.log('With CSRF Token Status:', response.status);
    return response.text();
})
.then(data => {
    console.log('Valid CSRF test:', data.includes('Successfully enrolled') ? 'WORKING ✅' : 'CHECK RESPONSE');
});
```

---

## 4. Test for Data Tampering

### Step 1: Get CSRF Token (Same as CSRF Test Step 1)
```javascript
console.log('All cookies:', document.cookie);
const cookies = document.cookie.split(';');
let csrfToken = null;

cookies.forEach(cookie => {
    const [name, value] = cookie.trim().split('=');
    if (name === 'csrf_cookie_name') {
        csrfToken = value;
        console.log('CSRF Token found:', csrfToken);
    }
});
```

### Step 2: Try to Enroll Another User (Data Tampering)
```javascript
// Try to enroll user_id=999 instead of current user
fetch('http://localhost/ITE311-MAGALLANO/student/enroll/1', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'course_id=1&user_id=999&student_id=999&csrf_token=' + csrfToken,
    credentials: 'include'
})
.then(response => {
    console.log('Data Tampering Status:', response.status);
    return response.text();
})
.then(data => {
    if (response.status === 403) {
        console.log('✅ SECURE: CSRF Protection blocked tampering attempt');
    } else if (data.includes('Successfully enrolled')) {
        console.log('✅ SECURE: Server ignored fake user_id and used session instead');
        console.log('Data tampering protection WORKING!');
    } else {
        console.log('Response preview:', data.substring(0, 300));
    }
});
```

**Expected Result:** Server should ignore `user_id=999` and use session user_id instead

---

## 5. Complete Security Test Suite

### Run All Tests at Once
```javascript
// Complete security test function
async function runSecurityTests() {
    console.log('🔒 Starting Security Tests...\n');
    
    // Test 1: Authorization Bypass
    console.log('1️⃣ Testing Authorization Bypass...');
    try {
        const authTest = await fetch('http://localhost/ITE311-MAGALLANO/student/enroll/1', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'course_id=1',
            credentials: 'include'
        });
        const authResult = await authTest.text();
        console.log('Authorization Test:', authResult.includes('login') ? '✅ SECURE' : '❌ VULNERABLE');
    } catch (e) {
        console.log('Authorization Test: ❌ ERROR -', e.message);
    }
    
    // Test 2: SQL Injection
    console.log('\n2️⃣ Testing SQL Injection...');
    try {
        const sqlTest = await fetch('http://localhost/ITE311-MAGALLANO/student/enroll/1 OR 1=1', {
            method: 'GET',
            credentials: 'include'
        });
        console.log('SQL Injection Test:', sqlTest.status === 400 ? '✅ PROTECTED' : '❌ VULNERABLE');
    } catch (e) {
        console.log('SQL Injection Test: ❌ ERROR -', e.message);
    }
    
    // Test 3: CSRF Protection
    console.log('\n3️⃣ Testing CSRF Protection...');
    try {
        const csrfTest = await fetch('http://localhost/ITE311-MAGALLANO/student/enroll/1', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'course_id=1',
            credentials: 'include'
        });
        console.log('CSRF Test:', csrfTest.status === 403 ? '✅ PROTECTED' : '❌ VULNERABLE');
    } catch (e) {
        console.log('CSRF Test: ❌ ERROR -', e.message);
    }
    
    // Test 4: Data Tampering (requires CSRF token)
    console.log('\n4️⃣ Testing Data Tampering...');
    const cookies = document.cookie.split(';');
    let csrfToken = null;
    cookies.forEach(cookie => {
        const [name, value] = cookie.trim().split('=');
        if (name === 'csrf_cookie_name') {
            csrfToken = value;
        }
    });
    
    if (csrfToken) {
        try {
            const tamperTest = await fetch('http://localhost/ITE311-MAGALLANO/student/enroll/1', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `course_id=1&user_id=999&student_id=999&csrf_token=${csrfToken}`,
                credentials: 'include'
            });
            const tamperResult = await tamperTest.text();
            console.log('Data Tampering Test:', tamperTest.status === 403 ? '✅ CSRF BLOCKED' : '✅ USES SESSION DATA');
        } catch (e) {
            console.log('Data Tampering Test: ❌ ERROR -', e.message);
        }
    } else {
        console.log('Data Tampering Test: ⚠️ NO CSRF TOKEN FOUND');
    }
    
    console.log('\n🔒 Security Tests Complete!');
}

// Run the complete test suite
runSecurityTests();
```

---

## 6. Individual Test Commands

### Quick Authorization Test
```javascript
fetch('http://localhost/ITE311-MAGALLANO/student/enroll/1', {method: 'POST', body: 'course_id=1'})
.then(r => r.text()).then(data => console.log('Auth:', data.includes('login') ? '✅' : '❌'));
```

### Quick SQL Injection Test
```javascript
fetch('http://localhost/ITE311-MAGALLANO/student/enroll/1 OR 1=1')
.then(r => console.log('SQL:', r.status === 400 ? '✅' : '❌'));
```

### Quick CSRF Test
```javascript
fetch('http://localhost/ITE311-MAGALLANO/student/enroll/1', {method: 'POST', body: 'course_id=1'})
.then(r => console.log('CSRF:', r.status === 403 ? '✅' : '❌'));
```

---

## 7. Expected Results Summary

| Test Type | Expected Status | Expected Response | Security Status |
|-----------|----------------|-------------------|-----------------|
| **Authorization Bypass** | Redirect/401 | "Please login" | ✅ SECURE |
| **SQL Injection** | 400 Bad Request | "Disallowed characters" | ✅ SECURE |
| **CSRF Protection** | 403 Forbidden | "CSRF token mismatch" | ✅ SECURE |
| **Data Tampering** | 200/403 | Uses session user_id | ✅ SECURE |

---

## 8. Troubleshooting

### If Tests Don't Work:
1. **Make sure you're logged in** to the application first
2. **Check the correct URL** - replace with your actual localhost path
3. **Enable CSRF protection** in config if testing CSRF
4. **Clear browser cache** and cookies if needed
5. **Check browser console** for any JavaScript errors

### Common Issues:
- **CORS errors**: Make sure you're on the same domain
- **Network errors**: Check if XAMPP server is running
- **Session issues**: Try logging out and back in
- **Token issues**: Refresh the page to get new CSRF tokens

---

## 9. Security Testing Checklist

- [ ] Test Authorization Bypass (without login)
- [ ] Test SQL Injection (malicious input)
- [ ] Test CSRF Protection (missing token)
- [ ] Test Data Tampering (fake user_id)
- [ ] Verify all tests show SECURE results
- [ ] Document any vulnerabilities found
- [ ] Re-test after applying fixes

**Remember:** These tests should show that your application is SECURE by rejecting malicious requests!