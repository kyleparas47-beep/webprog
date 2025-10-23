# 🔧 Reorganization Instructions

## ⚠️ IMPORTANT: Read Before Starting

This guide will help you reorganize your files **manually** to avoid breaking your system.

---

## 📋 Step-by-Step Process

### **Step 1: Create New Folder Structure**

Run these commands in your `webprogsystem` folder:

```bash
# Create main folders
mkdir pages
mkdir api
mkdir includes
mkdir docs
mkdir sql

# Create subfolders for pages
mkdir pages\auth
mkdir pages\student
mkdir pages\admin
mkdir pages\shared

# Create subfolders for api
mkdir api\events
mkdir api\registration
mkdir api\attendance
mkdir api\auth

# Create subfolders for assets
mkdir assets\css
mkdir assets\js
mkdir assets\images
```

**OR** Use this Windows batch file (save as `create_structure.bat`):

```batch
@echo off
echo Creating folder structure...

mkdir pages
mkdir pages\auth
mkdir pages\student
mkdir pages\admin
mkdir pages\shared

mkdir api
mkdir api\events
mkdir api\registration
mkdir api\attendance
mkdir api\auth

mkdir includes

mkdir assets\css
mkdir assets\js
mkdir assets\images

mkdir docs
mkdir sql

echo Done! Folder structure created.
pause
```

---

### **Step 2: Move Files to New Locations**

#### **A. Move Page Files**

**Auth Pages:**
```
MOVE login_register.php → pages/auth/login_register.php
MOVE forgot_password.php → pages/auth/forgot_password.php
MOVE verify_code.php → pages/auth/verify_code.php
MOVE reset_password.php → pages/auth/reset_password.php
```

**Student Pages:**
```
RENAME student_page.php → pages/student/dashboard.php
MOVE student_calendar.php → pages/student/calendar.php
RENAME MyProfilePage.php → pages/student/profile.php
MOVE view_ticket.php → pages/student/view_ticket.php
```

**Admin Pages:**
```
RENAME admin_page.php → pages/admin/dashboard.php
RENAME admin_calendar.php → pages/admin/calendar.php
MOVE attendance.php → pages/admin/attendance.php
MOVE edit_event_page.php → pages/admin/edit_event_page.php
MOVE delete_event_page.php → pages/admin/delete_event_page.php
```

**Shared Pages:**
```
MOVE view_events.php → pages/shared/view_events.php
```

#### **B. Move API/Handler Files**

**Event APIs:**
```
MOVE add_event.php → api/events/add_event.php
MOVE edit_event.php → api/events/edit_event.php
MOVE delete_event.php → api/events/delete_event.php
MOVE get_events.php → api/events/get_events.php
MOVE update_university_calendar.php → api/events/update_university_calendar.php
```

**Registration APIs:**
```
MOVE register_event.php → api/registration/register_event.php
MOVE get_registered_events.php → api/registration/get_registered_events.php
MOVE get_registration_info.php → api/registration/get_registration_info.php
```

**Attendance APIs:**
```
MOVE verify_ticket.php → api/attendance/verify_ticket.php
MOVE mark_attendance.php → api/attendance/mark_attendance.php
```

**Auth APIs:**
```
MOVE verify_code_handler.php → api/auth/verify_code_handler.php
MOVE reset_passwordhandler.php → api/auth/reset_passwordhandler.php
MOVE resend_code.php → api/auth/resend_code.php
MOVE logout.php → api/auth/logout.php
```

#### **C. Move Include Files**

```
MOVE config.php → includes/config.php
MOVE profile_menu_popup.php → includes/profile_menu_popup.php
```

#### **D. Move Asset Files**

**CSS:**
```
MOVE style.css → assets/css/style.css
MOVE student.css → assets/css/student.css
MOVE calendar_admin.css → assets/css/calendar_admin.css
```

**JavaScript:**
```
MOVE script.js → assets/js/script.js
MOVE calendar_admin.js → assets/js/calendar_admin.js
MOVE calendar_functions.js → assets/js/calendar_functions.js
```

**Images:**
```
MOVE assets\*.jpg → assets/images/
MOVE assets\*.png → assets/images/
```

#### **E. Move Documentation Files**

```
MOVE DATABASE_SETUP_GUIDE.txt → docs/DATABASE_SETUP_GUIDE.txt
MOVE EMAIL_SETUP_GUIDE.md → docs/EMAIL_SETUP_GUIDE.md
MOVE PASSWORD_RESET_EMAIL_SUMMARY.md → docs/PASSWORD_RESET_EMAIL_SUMMARY.md
```

#### **F. Move SQL Files**

```
MOVE update_database_attendance.sql → sql/update_database_attendance.sql
```

---

### **Step 3: Update File Paths**

Now you need to update paths in moved files. Here are the patterns:

#### **Pattern 1: Files in pages/auth/**

```php
// OLD
require_once 'config.php';
include 'profile_menu_popup.php';

// NEW
require_once '../../includes/config.php';
include '../../includes/profile_menu_popup.php';
```

```html
<!-- OLD -->
<link rel="stylesheet" href="style.css">
<script src="script.js"></script>

<!-- NEW -->
<link rel="stylesheet" href="../../assets/css/style.css">
<script src="../../assets/js/script.js"></script>
```

```php
// OLD
<form action="login_register.php">

// NEW
<form action="pages/auth/login_register.php">
```

#### **Pattern 2: Files in pages/student/ or pages/admin/**

Same as above - use `../../` to go up two levels.

#### **Pattern 3: Files in api/**

```php
// OLD
require_once 'config.php';

// NEW
require_once '../../includes/config.php';
```

#### **Pattern 4: index.php (root level)**

```html
<!-- OLD -->
<link rel="stylesheet" href="style.css">

<!-- NEW -->
<link rel="stylesheet" href="assets/css/style.css">
```

```javascript
// OLD
<script src="script.js"></script>

// NEW
<script src="assets/js/script.js"></script>
```

```php
// OLD
<form action="login_register.php">

// NEW
<form action="pages/auth/login_register.php">
```

---

## 🛠️ Automated Update Script (Optional)

Save this as `update_paths.php` and run it to help update paths:

```php
<?php
// Path updater helper (shows what needs updating)

function scanDirectory($dir) {
    $files = [];
    $items = scandir($dir);
    
    foreach ($items as $item) {
        if ($item == '.' || $item == '..') continue;
        
        $path = $dir . '/' . $item;
        
        if (is_dir($path)) {
            $files = array_merge($files, scanDirectory($path));
        } elseif (pathinfo($path, PATHINFO_EXTENSION) == 'php') {
            $files[] = $path;
        }
    }
    
    return $files;
}

// Get all PHP files
$phpFiles = scanDirectory('.');

echo "Files that may need path updates:\n\n";

foreach ($phpFiles as $file) {
    $content = file_get_contents($file);
    
    // Check for old patterns
    $needsUpdate = false;
    
    if (strpos($content, "require_once 'config.php'") !== false) {
        echo "❌ $file - Contains: require_once 'config.php'\n";
        $needsUpdate = true;
    }
    
    if (strpos($content, 'include \'profile_menu_popup.php\'') !== false) {
        echo "❌ $file - Contains: include 'profile_menu_popup.php'\n";
        $needsUpdate = true;
    }
    
    if (strpos($content, 'href="style.css"') !== false) {
        echo "❌ $file - Contains: href=\"style.css\"\n";
        $needsUpdate = true;
    }
    
    if ($needsUpdate) {
        echo "   → Check this file and update paths!\n\n";
    }
}

echo "\nScan complete!\n";
?>
```

---

## ✅ Verification Checklist

After reorganization, verify:

- [ ] Login page works (`index.php`)
- [ ] Can access student dashboard
- [ ] Can access admin dashboard  
- [ ] CSS and JS files loading correctly
- [ ] Images displaying properly
- [ ] Forms submitting correctly
- [ ] AJAX calls working
- [ ] Database connection working
- [ ] Session handling working

---

## 🔍 Testing After Reorganization

1. **Test Login**
   - Visit `http://localhost/webprogsystem/`
   - Try logging in

2. **Test Student Pages**
   - Visit student dashboard
   - Check if CSS loads
   - Check if calendar works

3. **Test Admin Pages**
   - Visit admin dashboard
   - Test event creation
   - Test attendance

4. **Check Browser Console**
   - Press F12
   - Look for 404 errors (missing files)
   - Look for JavaScript errors

---

## ⚠️ Common Issues & Fixes

### Issue: CSS not loading
**Fix:** Update CSS paths in HTML to use `../../assets/css/filename.css`

### Issue: JavaScript not loading  
**Fix:** Update JS paths to use `../../assets/js/filename.js`

### Issue: Config.php not found
**Fix:** Update to `require_once '../../includes/config.php';`

### Issue: Form not submitting
**Fix:** Update form action to point to new file location

### Issue: Images broken
**Fix:** Update image src to `../../assets/images/filename.jpg`

---

## 💡 Pro Tips

1. **Do it in stages** - Move one folder at a time
2. **Test after each stage** - Don't move everything at once
3. **Keep backups** - Copy your folder before starting
4. **Use search & replace** - In your IDE for bulk updates
5. **Check console** - Browser console shows missing files

---

## 📞 Need Help?

If you get stuck:
1. Check FILE_STRUCTURE.md for the plan
2. Look at path examples above
3. Use browser console to find 404 errors
4. Move one file at a time if needed

---

**Good luck with reorganization! Take it slow and test often.** 🚀

