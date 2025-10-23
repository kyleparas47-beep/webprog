# 📁 Organized File Structure Guide

## 🎯 New Organization (Simple & Clean)

```
webprogsystem/
│
├── index.php                 # Main entry point (Login page)
│
├── pages/                    # 📄 All User-Facing Pages
│   ├── auth/                # Authentication pages
│   │   ├── login_register.php
│   │   ├── forgot_password.php
│   │   ├── verify_code.php
│   │   └── reset_password.php
│   │
│   ├── student/             # Student pages
│   │   ├── dashboard.php
│   │   ├── calendar.php
│   │   ├── profile.php
│   │   └── view_ticket.php
│   │
│   ├── admin/               # Admin pages
│   │   ├── dashboard.php
│   │   ├── calendar.php
│   │   ├── attendance.php
│   │   └── add_event.php
│   │
│   └── shared/              # Pages used by both roles
│       └── view_events.php
│
├── api/                      # 🔌 AJAX Endpoints & Handlers
│   ├── events/
│   │   ├── get_events.php
│   │   ├── add_event.php
│   │   ├── edit_event.php
│   │   ├── delete_event.php
│   │   └── update_university_calendar.php
│   │
│   ├── registration/
│   │   ├── register_event.php
│   │   ├── get_registered_events.php
│   │   └── get_registration_info.php
│   │
│   ├── attendance/
│   │   ├── verify_ticket.php
│   │   └── mark_attendance.php
│   │
│   └── auth/
│       ├── verify_code_handler.php
│       ├── reset_passwordhandler.php
│       ├── resend_code.php
│       └── logout.php
│
├── includes/                 # 🔧 Reusable Components
│   ├── config.php           # Database configuration
│   ├── profile_menu_popup.php
│   └── functions.php        # Helper functions (to create)
│
├── assets/                   # 🎨 Static Resources
│   ├── css/
│   │   ├── style.css        # Auth pages style
│   │   ├── student.css      # Student/Admin layout
│   │   └── calendar_admin.css
│   │
│   ├── js/
│   │   ├── script.js        # Main JS
│   │   ├── calendar_admin.js
│   │   └── calendar_functions.js
│   │
│   └── images/
│       ├── logo.png
│       └── [other images]
│
├── docs/                     # 📚 Documentation
│   ├── DATABASE_SETUP_GUIDE.txt
│   ├── EMAIL_SETUP_GUIDE.md
│   └── PASSWORD_RESET_EMAIL_SUMMARY.md
│
├── sql/                      # 💾 Database Files
│   └── update_database_attendance.sql
│
└── test_email_config.php    # Testing utility

```

## 🔑 Key Principles

### **1. Pages (User-Facing)**
All files that users directly visit:
- Auth pages (login, register, forgot password)
- Student pages (dashboard, calendar, profile)
- Admin pages (dashboard, calendar, attendance)
- Shared pages (view events)

### **2. API (Backend Handlers)**
All files that handle AJAX requests or form submissions:
- Event management endpoints
- Registration endpoints
- Attendance endpoints
- Auth handlers

### **3. Includes (Reusable Code)**
Files that are included in multiple pages:
- Database config
- Shared components (profile menu)
- Helper functions

### **4. Assets (Static Files)**
- CSS files organized by purpose
- JavaScript files
- Images and media

### **5. Docs & SQL**
- Documentation files
- Database schema and updates

## 📋 File Mapping (Where Each File Goes)

| Current File | New Location | Type |
|--------------|--------------|------|
| `index.php` | `index.php` (root) | Entry point |
| `login_register.php` | `pages/auth/login_register.php` | Page |
| `forgot_password.php` | `pages/auth/forgot_password.php` | Page |
| `verify_code.php` | `pages/auth/verify_code.php` | Page |
| `reset_password.php` | `pages/auth/reset_password.php` | Page |
| `student_page.php` | `pages/student/dashboard.php` | Page |
| `student_calendar.php` | `pages/student/calendar.php` | Page |
| `MyProfilePage.php` | `pages/student/profile.php` | Page |
| `view_ticket.php` | `pages/student/view_ticket.php` | Page |
| `admin_page.php` | `pages/admin/dashboard.php` | Page |
| `admin_calendar.php` | `pages/admin/calendar.php` | Page |
| `attendance.php` | `pages/admin/attendance.php` | Page |
| `add_event.php` | `api/events/add_event.php` | API |
| `edit_event.php` | `api/events/edit_event.php` | API |
| `edit_event_page.php` | `pages/admin/edit_event_page.php` | Page |
| `delete_event.php` | `api/events/delete_event.php` | API |
| `delete_event_page.php` | `pages/admin/delete_event_page.php` | Page |
| `view_events.php` | `pages/shared/view_events.php` | Page |
| `get_events.php` | `api/events/get_events.php` | API |
| `update_university_calendar.php` | `api/events/update_university_calendar.php` | API |
| `register_event.php` | `api/registration/register_event.php` | API |
| `get_registered_events.php` | `api/registration/get_registered_events.php` | API |
| `get_registration_info.php` | `api/registration/get_registration_info.php` | API |
| `verify_ticket.php` | `api/attendance/verify_ticket.php` | API |
| `mark_attendance.php` | `api/attendance/mark_attendance.php` | API |
| `verify_code_handler.php` | `api/auth/verify_code_handler.php` | API |
| `reset_passwordhandler.php` | `api/auth/reset_passwordhandler.php` | API |
| `resend_code.php` | `api/auth/resend_code.php` | API |
| `logout.php` | `api/auth/logout.php` | API |
| `config.php` | `includes/config.php` | Include |
| `profile_menu_popup.php` | `includes/profile_menu_popup.php` | Include |
| `style.css` | `assets/css/style.css` | CSS |
| `student.css` | `assets/css/student.css` | CSS |
| `calendar_admin.css` | `assets/css/calendar_admin.css` | CSS |
| `script.js` | `assets/js/script.js` | JS |
| `calendar_admin.js` | `assets/js/calendar_admin.js` | JS |
| `calendar_functions.js` | `assets/js/calendar_functions.js` | JS |
| `assets/*` | `assets/images/*` | Images |
| `DATABASE_SETUP_GUIDE.txt` | `docs/DATABASE_SETUP_GUIDE.txt` | Doc |
| `EMAIL_SETUP_GUIDE.md` | `docs/EMAIL_SETUP_GUIDE.md` | Doc |
| `PASSWORD_RESET_EMAIL_SUMMARY.md` | `docs/PASSWORD_RESET_EMAIL_SUMMARY.md` | Doc |
| `update_database_attendance.sql` | `sql/update_database_attendance.sql` | SQL |

## 🔄 Path Updates Needed

### In Pages (going one level deeper):
```php
// OLD
require_once 'config.php';
include 'profile_menu_popup.php';
<link rel="stylesheet" href="style.css">
<script src="script.js"></script>

// NEW
require_once '../../includes/config.php';
include '../../includes/profile_menu_popup.php';
<link rel="stylesheet" href="../../assets/css/style.css">
<script src="../../assets/js/script.js"></script>
```

### In API Files (going one level deeper):
```php
// OLD
require_once 'config.php';

// NEW  
require_once '../../includes/config.php';
```

### Form Actions:
```php
// OLD
<form action="login_register.php" method="post">

// NEW
<form action="pages/auth/login_register.php" method="post">
```

### Links:
```php
// OLD
<a href="student_page.php">Dashboard</a>

// NEW
<a href="pages/student/dashboard.php">Dashboard</a>
```

## ✅ Benefits of This Structure

✅ **Clear Organization** - Know where every file belongs
✅ **Easy Navigation** - Find files quickly by category
✅ **Separation of Concerns** - Pages, API, and resources separated
✅ **Scalability** - Easy to add new features
✅ **No Breaking Changes** - Just moving files, not rewriting
✅ **Professional** - Industry-standard folder organization
✅ **Team-Friendly** - Other developers can understand structure

## 🚀 How to Use After Reorganization

### Access Points:
- **Login**: `http://localhost/webprogsystem/`
- **Student Dashboard**: `http://localhost/webprogsystem/pages/student/dashboard.php`
- **Admin Dashboard**: `http://localhost/webprogsystem/pages/admin/dashboard.php`
- **View Events**: `http://localhost/webprogsystem/pages/shared/view_events.php`

### Development:
- **Adding new student page**: Put in `pages/student/`
- **Adding new admin page**: Put in `pages/admin/`
- **Adding new API endpoint**: Put in appropriate `api/` subfolder
- **Adding new CSS**: Put in `assets/css/`
- **Adding new JS**: Put in `assets/js/`

## 📝 Notes

- `index.php` stays in root as main entry point
- All paths will be updated to use `../../` to go up directories
- Documentation clearly separated in `docs/`
- SQL files in dedicated `sql/` folder
- Testing files can stay in root or move to a `tests/` folder

---

This organization makes your codebase **professional, maintainable, and easy to navigate** without the complexity of MVC!

