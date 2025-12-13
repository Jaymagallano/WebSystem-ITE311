# 📚 Complete Materials Upload & Download Testing Guide

## 🎯 Current System Status

✅ **Server Running:** http://localhost:8000  
✅ **Database:** lms_magallano (Connected)  
✅ **Test Users:** Created  
✅ **Course:** "asd" (Course ID: 2)  
✅ **Enrollment:** Student User enrolled in course "asd"  
✅ **Materials:** 1 material already uploaded  
✅ **Student2:** Created (NOT enrolled) for access testing

---

## 👥 Test Accounts

| Role | Email | Password | Status |
|------|-------|----------|--------|
| Teacher | teacher@test.com | password123 | ✅ Ready |
| Student (Enrolled) | student@test.com | password123 | ✅ Enrolled in "asd" |
| Student2 (Not Enrolled) | student2@test.com | password123 | ✅ NOT enrolled |
| Admin | admin@test.com | password123 | ✅ Ready |

---

## 🧪 COMPLETE TEST FLOW

### ✅ TEST 1: Login as Teacher and Upload Material

#### Step 1.1: Login
1. Navigate to: http://localhost:8000/login
2. Enter credentials:
   - Email: `teacher@test.com`
   - Password: `password123`
3. Click "Login"
4. **Expected:** Redirected to teacher dashboard

#### Step 1.2: Navigate to Materials
1. Click "Materials" in sidebar OR
2. Navigate to: http://localhost:8000/teacher/materials
3. **Expected:** See materials page with course filter

#### Step 1.3: Upload New Material
1. Click "Upload Material" button OR
2. Navigate to: http://localhost:8000/teacher/upload_material
3. Fill in the form:
   - **Title:** "Test PDF Material"
   - **Description:** "This is a test PDF for materials testing"
   - **Course:** Select "asd" from dropdown
   - **File:** Choose a PDF or PPT file from your computer
4. Click "Upload Material"
5. **Expected:** 
   - Success message appears
   - Redirected to materials list
   - New material appears in the list

---

### ✅ TEST 2: Verify File is Saved

#### Step 2.1: Check Upload Folder
1. Open File Explorer
2. Navigate to: `C:\xampp\htdocs\ITE311-MAGALLANO\uploads\materials\`
3. **Expected:** 
   - Your uploaded file is present
   - File has correct size (not 0 bytes)
   - File can be opened

#### Step 2.2: Check Database Record
1. Run the test script:
   ```bash
   php test_materials_flow.php
   ```
2. **Expected:**
   - Material appears in "TEST 4: Checking Uploaded Materials"
   - File path is correct
   - File exists: ✅
   - Size is displayed correctly

---

### ✅ TEST 3: Login as Enrolled Student

#### Step 3.1: Logout and Login as Student
1. Click "Logout" in teacher dashboard
2. Navigate to: http://localhost:8000/login
3. Enter credentials:
   - Email: `student@test.com`
   - Password: `password123`
4. Click "Login"
5. **Expected:** Redirected to student dashboard

#### Step 3.2: Navigate to Resources
1. Click "Resources" in sidebar OR
2. Navigate to: http://localhost:8000/student/resources
3. **Expected:** See resources page with course dropdown

#### Step 3.3: Select Course and View Materials
1. Select "asd" from course dropdown
2. **Expected:**
   - Page refreshes and shows materials table
   - All uploaded materials for "asd" course are listed
   - Each material shows:
     - Title and description
     - File type badge
     - Upload date
     - Download button

---

### ✅ TEST 4: Test Download Functionality (Enrolled Student)

#### Step 4.1: Download via Resources Page
1. While on: http://localhost:8000/student/resources/2
2. Click the "Download" button next to a material
3. **Expected:**
   - File download starts immediately
   - File saves to your Downloads folder
   - Downloaded file can be opened successfully
   - No errors displayed

#### Step 4.2: Test Direct Download Link
1. Navigate to: http://localhost:8000/student/download_material/1
2. **Expected:**
   - File downloads immediately
   - ✅ Access granted (because student IS enrolled)
   - File is the same as uploaded

---

### ✅ TEST 5: Test Access Restriction (Non-Enrolled Student)

#### Step 5.1: Logout and Login as Student2
1. Click "Logout"
2. Navigate to: http://localhost:8000/login
3. Enter credentials:
   - Email: `student2@test.com`
   - Password: `password123`
4. Click "Login"
5. **Expected:** Redirected to student dashboard

#### Step 5.2: Try to View Course Materials
1. Navigate to: http://localhost:8000/student/resources
2. Select "asd" from dropdown
3. **Expected:**
   - ❌ **Error:** "Access denied." message appears
   - Redirected back to resources page
   - Cannot see materials (not enrolled)

#### Step 5.3: Try Direct Download Link
1. Navigate to: http://localhost:8000/student/download_material/1
2. **Expected:**
   - ❌ **Error:** "Access denied." flash message
   - Redirected to: http://localhost:8000/student/resources
   - NO file download occurs
   - ✅ **Security working!** Non-enrolled student blocked

---

### ✅ TEST 6: Verify Access Control in Course Details

#### Step 6.1: Test as Enrolled Student
1. Login as: student@test.com / password123
2. Navigate to: http://localhost:8000/student/course_details/2
3. **Expected:**
   - Course details page loads
   - Materials section shows uploaded materials
   - Download buttons work

#### Step 6.2: Test as Non-Enrolled Student
1. Login as: student2@test.com / password123
2. Try to navigate to: http://localhost:8000/student/course_details/2
3. **Expected:**
   - ❌ Access denied (not enrolled)
   - Redirected away from page

---

## 📊 Test Results Checklist

Use this checklist to track your testing:

### Upload Tests
- [ ] Teacher can login successfully
- [ ] Materials page loads correctly
- [ ] Upload form displays all fields
- [ ] File upload succeeds with valid file
- [ ] Success message appears after upload
- [ ] Material appears in materials list

### File Storage Tests
- [ ] File exists in uploads/materials/ folder
- [ ] File size is correct (not 0 bytes)
- [ ] File can be opened from folder
- [ ] Database record created in `materials` table
- [ ] File path stored correctly in database

### Student Access Tests (Enrolled)
- [ ] Student can view resources page
- [ ] Course dropdown shows enrolled courses
- [ ] Materials list displays after selecting course
- [ ] All material details shown correctly
- [ ] Download button visible
- [ ] Download works from resources page
- [ ] Direct download link works

### Security Tests (Non-Enrolled)
- [ ] Student2 cannot access resources page for unenrolled course
- [ ] "Access denied" message appears
- [ ] Direct download link blocked
- [ ] No file download occurs
- [ ] Properly redirected after denial
- [ ] Cannot view course details if not enrolled

---

## 🔍 Additional Verification

### Database Verification
Run this SQL query to verify everything:

```sql
-- Check materials
SELECT m.id, m.title, m.file_name, m.course_id, c.code, c.title as course_title
FROM materials m
JOIN courses c ON m.course_id = c.id
ORDER BY m.created_at DESC;

-- Check enrollments
SELECT e.*, u.name, u.email, c.code
FROM enrollments e
JOIN users u ON e.student_id = u.id
JOIN courses c ON e.course_id = c.id;

-- Check users
SELECT id, name, email, role FROM users;
```

### File System Verification
```bash
# List uploaded files
dir C:\xampp\htdocs\ITE311-MAGALLANO\uploads\materials\

# Check file permissions (should be readable)
```

---

## 🎨 Visual Test Points

### Teacher Materials Page
- [ ] Professional layout
- [ ] Course filter dropdown works
- [ ] Upload button prominent
- [ ] Materials table shows all info
- [ ] Delete buttons have confirmation
- [ ] File type badges colored correctly

### Student Resources Page
- [ ] Clean, simple interface
- [ ] Course selector easy to use
- [ ] Materials clearly displayed
- [ ] Download buttons prominent
- [ ] File type icons visible
- [ ] Upload dates formatted nicely

---

## 🚨 Common Issues & Solutions

### Issue: File not uploading
**Solution:**
- Check uploads/materials/ folder exists
- Verify folder has write permissions (0777)
- Check file size < 10MB
- Verify file type is allowed

### Issue: "Access denied" for enrolled student
**Solution:**
- Verify enrollment in database
- Check course_id matches
- Re-login to refresh session

### Issue: Download not working
**Solution:**
- Verify file exists in uploads folder
- Check file_path in database is correct
- Ensure download helper is loaded

---

## ✅ SUCCESS CRITERIA

All tests PASS if:

1. ✅ Teacher can upload files successfully
2. ✅ Files saved in correct folder with correct name
3. ✅ Database records created with accurate information
4. ✅ Enrolled students CAN download materials
5. ✅ Non-enrolled students CANNOT download materials
6. ✅ Access control properly enforced
7. ✅ No errors in browser console
8. ✅ Flash messages display correctly
9. ✅ Redirects work as expected
10. ✅ Downloaded files are not corrupted

---

## 🎯 Quick Test Commands

```bash
# Start server
cd C:\xampp\htdocs\ITE311-MAGALLANO
php -S localhost:8000

# Run verification script
php test_materials_flow.php

# Create test student2
php create_test_student2.php

# Check logs (if issues occur)
tail -f application/logs/log-*.php
```

---

## 📝 Test Report Template

Copy this and fill it out:

```
TEST DATE: _______________
TESTER: _______________

UPLOAD FUNCTIONALITY: [ ] PASS [ ] FAIL
- Notes: _______________

FILE STORAGE: [ ] PASS [ ] FAIL
- Notes: _______________

ENROLLED ACCESS: [ ] PASS [ ] FAIL
- Notes: _______________

ACCESS RESTRICTION: [ ] PASS [ ] FAIL
- Notes: _______________

OVERALL RESULT: [ ] ALL TESTS PASSED [ ] ISSUES FOUND

Issues/Bugs Found:
1. _______________
2. _______________
3. _______________
```

---

## 🎉 You're All Set!

Your application is ready for complete materials testing!  
Follow the steps above and check off each item.  
Have fun testing! 🚀
