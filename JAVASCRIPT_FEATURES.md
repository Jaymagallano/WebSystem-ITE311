# 🚀 AdminLTE Dashboard JavaScript Features

## Overview
Complete interactive features for Admin, Teacher, and Student dashboards with modern animations and user experience enhancements.

---

## 📋 Features List

### 1. ✨ Stat Cards Animation
- **Fade-in animation** on page load
- **Staggered entrance** (100ms delay between cards)
- **Hover elevation** effect
- **Count-up animation** for numbers

```javascript
// Usage: Automatically applied to .stat-card elements
```

### 2. 🔢 Count Up Animation
- Animates numbers from 0 to target value
- 2-second smooth animation
- Applied to all stat card numbers

### 3. 💡 Tooltips & Popovers
- Bootstrap 5 tooltips
- Bootstrap 5 popovers
- Auto-initialization

```html
<!-- Tooltip -->
<button data-bs-toggle="tooltip" title="Click me!">Hover</button>

<!-- Popover -->
<button data-bs-toggle="popover" title="Title" data-bs-content="Content">Click</button>
```

### 4. 🔄 Card Refresh
- Refresh card content with loading animation
- Success toast notification
- Smooth opacity transition

```html
<button data-card-widget="refresh">
    <i class="bi bi-arrow-clockwise"></i>
</button>
```

### 5. 📦 Card Collapse
- Expand/collapse card body
- Smooth slide animation
- Icon rotation

```html
<button data-card-widget="collapse">
    <i class="bi bi-chevron-up"></i>
</button>
```

### 6. 🔍 Search Filter
- Real-time table filtering
- Case-insensitive search
- Instant results

```html
<input type="text" id="tableSearch" placeholder="Search...">
```

### 7. 🔔 Notifications
- Mark as read functionality
- Clear all notifications
- Fade-out animation
- Toast notifications

### 8. 📱 Sidebar Toggle
- Collapse/expand sidebar
- LocalStorage state persistence
- Smooth transition
- Mobile-friendly

```html
<button data-widget="pushmenu">
    <i class="bi bi-list"></i>
</button>
```

### 9. 🎯 Smooth Scroll
- Smooth scrolling to anchor links
- 800ms animation duration
- Offset for fixed headers

### 10. ⏰ Alert Auto Close
- Auto-close alerts after 5 seconds
- Fade-out animation
- Excludes `.alert-permanent` class

### 11. 📊 Table Actions
- **Delete**: Confirm dialog + fade-out
- **Edit**: Coming soon notification
- **View**: Coming soon notification

```html
<button class="btn btn-sm btn-delete">Delete</button>
<button class="btn btn-sm btn-edit">Edit</button>
<button class="btn btn-sm btn-view">View</button>
```

### 12. ⚡ Quick Actions
- Hover slide effect
- Smooth transitions
- Visual feedback

### 13. ⏳ Loading States
- Button loading spinner
- Disabled state during submission
- Auto-restore after 3 seconds

### 14. 🍞 Toast Notifications
- 4 types: success, error, warning, info
- Auto-dismiss after 3 seconds
- Slide-in animation
- Color-coded with icons

```javascript
// Usage
AdminLTEDashboard.showToast('Success message!', 'success');
AdminLTEDashboard.showToast('Error occurred!', 'error');
AdminLTEDashboard.showToast('Warning!', 'warning');
AdminLTEDashboard.showToast('Information', 'info');
```

### 15. ❓ Confirm Dialog
- Native confirm dialog wrapper
- Callback support

```javascript
AdminLTEDashboard.confirmDialog('Are you sure?', function() {
    // Action on confirm
});
```

### 16. 🔄 Loading Overlay
- Full-screen loading overlay
- Centered spinner
- Semi-transparent backdrop

```javascript
// Show loading
AdminLTEDashboard.showLoading();

// Hide loading
AdminLTEDashboard.hideLoading();
```

### 17. 🔃 Refresh Data
- AJAX data refresh
- Loading overlay during fetch
- Success/error toast notifications

```javascript
AdminLTEDashboard.refreshData('/api/data', function(response) {
    // Handle response
});
```

---

## 🎨 CSS Animations Included

### Keyframe Animations
- `slideInRight` - Toast notifications
- `fadeIn` - Page content
- `slideDown` - Dropdowns
- `spin` - Loading spinners

### Transition Effects
- Card hover elevation
- Button hover lift
- Table row hover highlight
- Notification item slide
- Sidebar collapse/expand

### Special Effects
- Button ripple effect on click
- Smooth page transitions
- Card hover shadow
- Icon rotations

---

## 📦 Dependencies

### Required
- **jQuery** (3.x or higher)
- **Bootstrap 5** (for tooltips, popovers, modals)
- **Bootstrap Icons** (for icons)

### Optional
- Chart.js (for future chart implementations)
- DataTables (for advanced table features)

---

## 🚀 Usage

### Basic Setup

1. **Include jQuery and Bootstrap**
```html
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
```

2. **Include AdminLTE Dashboard JS**
```html
<script src="assets/js/adminlte-dashboard.js"></script>
```

3. **Auto-initialization**
The script automatically initializes on document ready.

### Manual Initialization
```javascript
$(document).ready(function() {
    AdminLTEDashboard.init();
});
```

---

## 🎯 API Methods

### Public Methods

```javascript
// Initialize all features
AdminLTEDashboard.init();

// Show toast notification
AdminLTEDashboard.showToast(message, type);

// Confirm dialog
AdminLTEDashboard.confirmDialog(message, callback);

// Show/hide loading overlay
AdminLTEDashboard.showLoading();
AdminLTEDashboard.hideLoading();

// Refresh data via AJAX
AdminLTEDashboard.refreshData(url, callback);
```

---

## 🎨 Color Scheme

Toast notification colors match the unique palette:
- **Success**: `#10b981` (Emerald)
- **Error**: `#ef4444` (Red)
- **Warning**: `#f59e0b` (Amber)
- **Info**: `#06b6d4` (Cyan)

---

## 📱 Responsive Features

- Mobile-friendly sidebar toggle
- Touch-friendly buttons
- Responsive animations
- Optimized for all screen sizes

---

## ⚡ Performance

- Efficient event delegation
- Minimal DOM manipulation
- Optimized animations (GPU-accelerated)
- LocalStorage for state persistence
- Debounced search filtering

---

## 🔧 Customization

### Modify Animation Duration
```javascript
// In initStatCards function
setTimeout(() => {
    // Change 100 to your preferred delay
}, index * 100);
```

### Change Toast Duration
```javascript
// In showToast function
setTimeout(() => {
    // Change 3000 to your preferred duration (ms)
}, 3000);
```

### Customize Colors
Edit the `bgColors` object in `showToast` function:
```javascript
const bgColors = {
    'success': '#10b981',
    'error': '#ef4444',
    'warning': '#f59e0b',
    'info': '#06b6d4'
};
```

---

## 🐛 Browser Support

✅ Chrome (latest)
✅ Firefox (latest)
✅ Safari (latest)
✅ Edge (latest)
✅ Mobile browsers

---

## 📝 Examples

### Example 1: Show Success Toast
```javascript
$('#saveButton').click(function() {
    // Save data...
    AdminLTEDashboard.showToast('Data saved successfully!', 'success');
});
```

### Example 2: Confirm Delete
```javascript
$('.delete-btn').click(function() {
    AdminLTEDashboard.confirmDialog('Delete this item?', function() {
        // Perform delete
        AdminLTEDashboard.showToast('Item deleted', 'success');
    });
});
```

### Example 3: Load Data with Loading Overlay
```javascript
$('#loadDataBtn').click(function() {
    AdminLTEDashboard.showLoading();
    
    $.ajax({
        url: '/api/data',
        success: function(data) {
            AdminLTEDashboard.hideLoading();
            AdminLTEDashboard.showToast('Data loaded!', 'success');
        }
    });
});
```

---

## 🎓 Best Practices

1. **Always use toast notifications** for user feedback
2. **Show loading states** during async operations
3. **Confirm destructive actions** (delete, etc.)
4. **Use smooth animations** for better UX
5. **Persist user preferences** (sidebar state, etc.)
6. **Handle errors gracefully** with error toasts
7. **Keep animations subtle** and professional

---

**File**: `assets/js/adminlte-dashboard.js`
**Version**: 1.0.0
**Last Updated**: December 14, 2025
**Author**: AdminLTE Dashboard Team
