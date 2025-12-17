# Vulnerability Checking via Browser Console

Since running commands in the terminal can be tricky on Windows (PowerShell vs Command Prompt), using the **Browser Developer Tools** is often easier and more accurate because it uses your actual login session.

### **How to Open the Console**
1.  Open your web application in Chrome, Edge, or Firefox.
2.  Press **F12** on your keyboard (or Right-click anywhere > **Inspect**).
3.  Click on the **"Console"** tab at the top of the developer tools panel.
4.  **Copy and Paste** the code blocks below into the console and press **Enter**.

---

### 1. Test for Authorization Bypass
**Objective**: Verify unauthorized users cannot enroll.

**Instructions**:
1.  **Log out** of the application first.
2.  Paste this code into the console:
```javascript
fetch('http://localhost/ITE311-MAGALLANO/student/enroll/1', { redirect: 'manual' })
    .then(response => {
        if (response.type === 'opaqueredirect' || response.status === 302 || response.url.includes('login')) {
            console.log('%c✅ PASS: Authorization check working (Redirected to login)', 'color: green');
        } else if (response.status === 200) {
            console.log('%c❌ FAIL: Endpoint accessible without login!', 'color: red');
        } else {
            console.log('Status:', response.status);
        }
    });
```
*Expected*: A redirect or error.

---

### 2. Test for SQL Injection
**Objective**: Test if the `course_id` validates input.

**Instructions**:
1.  Log in as a student.
2.  Paste this code:
```javascript
// Trying to inject "1 OR 1=1"
fetch('http://localhost/ITE311-MAGALLANO/student/enroll/1%20OR%201=1')
    .then(response => {
        console.log('Checked URL:', response.url);
        // If the server redirects back to the course list (due to intval check), it's safe.
        // If it crashes or shows a database error, it's vulnerable.
        if(response.url.includes('student/courses')) {
             console.log('%c✅ SAFE: Input was likely validated/sanitized', 'color: green');
        } else {
             console.log('%c⚠️ CHECK: Page did not redirect as expected. Check for DB errors on screen.', 'color: orange');
        }
    });
```
*Expected*: The generic error page or a redirect back to courses, NOT a database error.

---

### 3. Test for CSRF (Cross-Site Request Forgery)
**Objective**: Ensure sensitive actions require a token. 
*Note: Your current enrollment uses GET requests (links), which is technically a CSRF vulnerability itself. This test checks attempting a POST.*

**Instructions**:
1.  Log in as a student.
2.  Paste this code:
```javascript
// Attempt to enroll via POST without a CSRF token
fetch('http://localhost/ITE311-MAGALLANO/student/enroll/2', {
    method: 'POST',
    body: new URLSearchParams({ 'course_id': 2 }) // No csrf_test_name included
}).then(response => {
    if (response.status === 403) {
        console.log('%c✅ PASS: Request blocked by CSRF protection (403 Forbidden)', 'color: green');
    } else {
        console.log('%c❌ FAIL: Request was accepted without CSRF token (Status: ' + response.status + ')', 'color: red');
    }
});
```

---

### 4. Test for Data Tampering
**Objective**: Ensure you cannot enroll "User 999" while logged in as yourself.

**Instructions**:
1.  Log in as a student.
2.  Paste this code:
```javascript
// We try to tell the server explicitly "Enroll User 999"
// The server SHOULD ignore this and use your Session ID instead.
let formData = new FormData();
formData.append('student_id', '999'); 

fetch('http://localhost/ITE311-MAGALLANO/student/enroll/1', {
    method: 'POST', // or GET depending on how your controller reads data
    body: formData
}).then(res => {
     console.log('Request sent. Now checks your Database users/enrollments.');
     console.log('If User 999 is enrolled, the test FAILED.');
     console.log('If only YOU are enrolled, the test PASSED (Server used Session ID).');
});
```

---

### 5. Test for Input Validation
**Objective**: Ensure invalid course IDs are handled gracefully.

**Instructions**:
1.  Log in as a student.
2.  Paste this code:
```javascript
// Asking for Course ID 99999
fetch('http://localhost/ITE311-MAGALLANO/student/enroll/99999')
    .then(response => {
        // We expect a redirect back to the course list with an error flash message
        if (response.url.includes('student/courses')) {
            console.log('%c✅ PASS: Application handled invalid ID gracefully', 'color: green');
        } else {
            console.log('%c❌ FAIL: Unexpected behavior (Status: ' + response.status + ')', 'color: red');
        }
    });
```
