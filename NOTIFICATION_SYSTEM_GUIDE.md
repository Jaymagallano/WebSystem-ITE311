# 🔔 Real-Time Notification System - Complete Guide

## 📚 Overview

A fully functional, real-time notification system built with **jQuery AJAX**, **Bootstrap 5**, and **CodeIgniter 3** that provides seamless user experience without page reloads.

### ✨ Features

- 🔔 **Real-time notifications** - Auto-refreshes every 30 seconds
- 🎨 **Beautiful Bootstrap UI** - Styled dropdown with color-coded icons
- ⚡ **AJAX-powered** - Asynchronous communication with server
- 📱 **Responsive design** - Works on all devices
- 🎯 **Role-based notifications** - Different notifications for students/teachers
- 🔵 **Badge counter** - Shows unread notification count
- ✅ **Mark as read** - Individual or bulk marking
- 🔗 **Clickable notifications** - Navigate to relevant pages

---

## 🎯 Notification Types

| Type | Icon Color | For | Triggered When |
|------|------------|-----|----------------|
| **Material** | 🔵 Blue | Students | Teacher uploads new material |
| **Assignment** | 🟠 Orange | Students | Teacher creates new assignment |
| **Grade** | 🟢 Green | Students | Teacher posts a grade |
| **Enrollment** | 🔵 Teal | Students | Student enrolls in course |
| **Submission** | 🟣 Purple | Teachers | Student submits assignment |

---

## 📁 Files Created

### 1. Database
- **Migration:** `application/migrations/010_create_notifications_table.php`
- **Setup Script:** `setup_notifications_table.php`
- **SQL File:** `create_notifications_table.sql`

### 2. Models
- **Notification_model:** `application/models/Notification_model.php`
  - Create notifications
  - Get unread/all notifications
  - Mark as read
  - Auto-notify for various events

### 3. Controllers
- **Notifications Controller:** `application/controllers/Notifications.php`
  - AJAX endpoints for fetching notifications
  - Mark as read functionality
  - Time ago formatting

### 4. Views
- **Updated header.php:** Added notification bell and dropdown HTML
- **Updated footer.php:** Added jQuery and notification script

### 5. Assets
- **JavaScript:** `assets/js/notifications.js`
  - AJAX requests
  - Real-time updates
  - UI interactions

### 6. Routes
Added to `application/config/routes.php`:
```php
$route['notifications/get_unread'] = 'notifications/get_unread';
$route['notifications/get_all'] = 'notifications/get_all';
$route['notifications/mark_read/(:num)'] = 'notifications/mark_read/$1';
$route['notifications/mark_all_read'] = 'notifications/mark_all_read';
```

### 7. Controller Updates
- **Teacher.php** - Sends notifications when:
  - Uploading materials
  - Creating assignments
  - Posting grades
  
- **Student.php** - Sends notifications when:
  - Enrolling in courses
  - Submitting assignments

---

## 🔧 Installation & Setup

### Step 1: Run Database Setup
```bash
php setup_notifications_table.php
```

### Step 2: Create Test Notifications
```bash
php test_notifications.php
```

### Step 3: Start Server
```bash
php -S localhost:8000
```

### Step 4: Test in Browser
Navigate to: `http://localhost:8000`

---

## 🎨 UI Components

### Notification Bell
Located in the top navigation bar:
- Shows red badge with unread count
- Animated pulse effect
- Clickable to toggle dropdown

### Notification Dropdown
- **Header:** "Notifications" with "Mark all read" button
- **List:** Scrollable list of notifications
- **Footer:** Optional links (future enhancement)
- **Empty State:** Displays when no notifications

### Notification Item
Each notification displays:
- **Icon:** Color-coded circle with type-specific icon
- **Title:** Bold notification title
- **Message:** Description text
- **Time:** "X minutes ago" format

---

## 📡 AJAX Endpoints

### 1. Get Unread Notifications
```javascript
GET /notifications/get_unread
```
**Response:**
```json
{
  "count": 4,
  "notifications": [
    {
      "id": 1,
      "type": "material",
      "title": "New Material Available",
      "message": "New material 'Intro to PHP' uploaded",
      "link": "student/resources/2",
      "time_ago": "5 minutes ago"
    }
  ]
}
```

### 2. Mark as Read
```javascript
GET /notifications/mark_read/1
```
**Response:**
```json
{
  "success": true
}
```

### 3. Mark All as Read
```javascript
GET /notifications/mark_all_read
```
**Response:**
```json
{
  "success": true
}
```

---

## 🔄 How It Works

### 1. Page Load
```
User loads page → notifications.js loads → AJAX call to get_unread → Update UI
```

### 2. Auto-Refresh (Every 30 seconds)
```
setInterval → AJAX call to get_unread → Update badge & dropdown
```

### 3. Click Notification
```
User clicks item → AJAX mark_read → Redirect to link → Refresh list
```

### 4. Mark All Read
```
User clicks "Mark all read" → AJAX mark_all_read → Clear badge → Show toast
```

---

## 🎯 Testing Scenarios

### Test 1: Student Login
1. Login as `student@test.com` / `password123`
2. Expected: See bell with badge showing "4"
3. Click bell → See 4 colored notifications
4. Click one → Redirects and marks as read

### Test 2: Teacher Login
1. Login as `teacher@test.com` / `password123`
2. Expected: See bell with badge showing "2"
3. Click bell → See submission notifications
4. Click "Mark all read" → Badge disappears

### Test 3: Real-Time Notifications
1. Login as Teacher
2. Upload a new material
3. Login as Student (different browser/incognito)
4. Wait up to 30 seconds
5. Expected: New notification appears automatically

### Test 4: Navigation
1. Click on a notification
2. Expected: Redirects to relevant page
3. Check bell → That notification is marked as read

---

## 🎨 Customization

### Change Auto-Refresh Interval
In `assets/js/notifications.js`:
```javascript
// Change 30000 (30 seconds) to desired milliseconds
setInterval(loadNotifications, 30000);
```

### Add New Notification Type
1. **Add icon class** in `getNotificationIcon()`:
```javascript
'new_type': 'bi-icon-name'
```

2. **Add CSS** in `header.php`:
```css
.notification-icon.new_type {
    background: linear-gradient(135deg, #color1 0%, #color2 100%);
    color: white;
}
```

3. **Create helper method** in `Notification_model.php`:
```php
public function notify_new_event($user_id, $data) {
    $this->create_notification([
        'user_id' => $user_id,
        'type' => 'new_type',
        'title' => 'Title',
        'message' => 'Message',
        'link' => 'controller/method'
    ]);
}
```

### Modify Notification Limit
In `Notification_model.php`:
```php
public function get_unread_notifications($user_id, $limit = 10) {
    // Change $limit = 10 to desired number
}
```

---

## 🚀 Advanced Features (Future Enhancements)

### 1. Sound Notifications
```javascript
function playNotificationSound() {
    const audio = new Audio(base_url + 'assets/sounds/notification.mp3');
    audio.play();
}
```

### 2. Push Notifications (Browser)
```javascript
if ('Notification' in window) {
    Notification.requestPermission();
}
```

### 3. WebSocket for Instant Updates
Replace polling with WebSocket connection for real-time push

### 4. Notification Preferences
Allow users to enable/disable notification types

### 5. Email Notifications
Send email in addition to in-app notifications

---

## 📊 Database Schema

```sql
CREATE TABLE `notifications` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_read` (`is_read`),
  CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) 
    REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

---

## 🐛 Troubleshooting

### Issue: No notifications showing
**Solution:** 
1. Check browser console for errors
2. Verify AJAX URL is correct (check base_url)
3. Ensure table exists: `php setup_notifications_table.php`

### Issue: Badge not updating
**Solution:**
1. Check if auto-refresh is working (console.log)
2. Verify notifications controller is accessible
3. Clear browser cache

### Issue: Notifications not triggering
**Solution:**
1. Verify Notification_model is loaded in controller
2. Check if notification methods are called
3. Review database for new entries

### Issue: Clicking notification doesn't redirect
**Solution:**
1. Check if link is valid in database
2. Verify base_url is correct in notifications.js
3. Check browser console for JavaScript errors

---

## ✅ Success Criteria

Your notification system is working correctly if:

- ✅ Badge shows correct unread count
- ✅ Dropdown displays notifications with icons
- ✅ Clicking notification marks as read and redirects
- ✅ "Mark all read" clears all notifications
- ✅ New notifications appear within 30 seconds
- ✅ Different user roles see appropriate notifications
- ✅ Time ago displays correctly
- ✅ Dropdown closes when clicking outside

---

## 📝 Code Examples

### Create Custom Notification
```php
// In any controller
$this->load->model('Notification_model');

$this->Notification_model->create_notification([
    'user_id' => 5,
    'type' => 'custom',
    'title' => 'Custom Notification',
    'message' => 'This is a custom message',
    'link' => 'controller/method/id'
]);
```

### Get Notifications in View
```php
// In controller
$this->load->model('Notification_model');
$data['notifications'] = $this->Notification_model->get_user_notifications(
    $this->session->userdata('user_id')
);
```

### AJAX Call Example
```javascript
$.ajax({
    url: baseUrl + 'notifications/get_unread',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
        console.log('Unread count:', response.count);
        console.log('Notifications:', response.notifications);
    }
});
```

---

## 🎉 Congratulations!

You've successfully implemented a modern, real-time notification system! Your LMS now provides:

- ✅ **Real-time feedback** - Users stay informed without refreshing
- ✅ **Professional UI** - Clean, modern Bootstrap design
- ✅ **AJAX-powered** - Smooth, asynchronous updates
- ✅ **Role-based** - Appropriate notifications for each user type
- ✅ **Scalable** - Easy to add new notification types

**Next steps:**
1. Test all scenarios
2. Customize colors/icons to match your brand
3. Add more notification types as needed
4. Consider adding email notifications
5. Implement sound/browser push notifications

---

**Happy Coding! 🚀**
