# NUEvents - Student Event Management System

## Overview
NUEvents (NUsync) is a PHP-based web application for managing student events at National University. It provides user authentication, event management, calendar functionality, and student pre-registration for both students and administrators.

## Project Structure

### Directory Organization
```
├── frontend/                  # Frontend files (pages, UI, assets)
│   ├── assets/               # Images and static resources
│   ├── *.php                 # User-facing pages
│   ├── *.css                 # Stylesheets
│   └── *.js                  # JavaScript files
├── backend/                   # Backend files (API, handlers, config)
│   ├── config.php            # Database configuration
│   ├── *_handler.php         # Request handlers
│   └── *.php                 # API endpoints
├── index.php                  # Root entry point (redirects to frontend)
├── *.sql                     # Database setup scripts
└── *.md                      # Documentation
```

### Frontend Files (`/frontend/`)
- **Pages**: index.php, student_page.php, admin_page.php, student_calendar.php, admin_calendar.php, MyProfilePage.php, reset_password.php, calendar.php, logout.html
- **Styles**: style.css, student.css, calendar_admin.css, stylescal.css, logout.css
- **Scripts**: script.js, scriptcal.js, calendar_admin.js, calendar_functions.js
- **Assets**: Images and logos in `/frontend/assets/`

### Backend Files (`/backend/`)
- **Configuration**: config.php
- **Authentication**: login_register.php, forgot_password.php, reset_passwordhandler.php, logout.php
- **Event Management**: add_event.php, edit_event.php, delete_event.php, get_events.php
- **Student Features**: register_event.php, get_registered_events.php
- **Admin Features**: update_university_calendar.php

## Key Features
- User registration and authentication (students and admins)
- **Forgot Password** functionality with secure token-based reset
- Event creation, editing, and deletion (admin)
- **Student Calendar**: View-only calendar with pre-registration functionality
- **Admin Calendar**: Full calendar management with create, edit, delete capabilities
- Event pre-registration system (students only)
- Calendar synchronization between admin and student views
- Role-based access control

## Database Tables
The application uses MySQL with the following tables:

### Core Tables
- `student`: User accounts (id, name, email, password, role, created_at)
- `events`: Event information (id, title, description, event_type, start_date, end_date, location, created_by, created_at)
- `password_resets`: Password reset tokens (id, user_id, token, expires_at, created_at)
- `event_registrations`: Student event pre-registrations (id, student_id, event_id, registered_at)

## Database Setup

### Quick Setup (Recommended)
Run the complete database setup script:
```bash
mysql -u your_username -p your_database_name < complete_database_setup.sql
```

### Individual Tables
- `student_table.sql` - User accounts table
- `database_setup.sql` - Events table only
- `password_resets_table.sql` - Forgot password functionality
- `event_registrations_table.sql` - Student pre-registration

**See `DATABASE_SETUP_INSTRUCTIONS.md` for detailed setup guide.**

## Calendar System

### Student Calendar (`/frontend/student_calendar.php`)
- **View Events**: Students can view all events added by admins
- **Pre-Registration**: Click on any event to pre-register
- **Success Popup**: Shows confirmation message upon successful registration
- **My Registered Events**: Right-side panel showing all pre-registered events
- **Search & Filter**: Search events by keyword, filter by type (Webinar, Seminars, Workshop)
- **Multiple Views**: Day, Week, and Month calendar views

### Admin Calendar (`/frontend/admin_calendar.php`)
- **Full Management**: Create, edit, and delete events
- **Save Updates Button**: Refreshes calendar state (bottom right)
- **Update University Calendar Button**: Synchronizes events to student calendar (bottom right)
- **Event Types**: Events, Webinar, Seminars, Workshop
- **Calendar Views**: Day, Week, and Month views

## Running the Application
The PHP server is configured to run on port 5000:
```bash
php -S 0.0.0.0:5000
```

Access at: `http://your-domain:5000`

## Default Admin Account
**Email:** admin@nu.edu.ph  
**Password:** admin123  
⚠️ **Change this password after first login!**

## Deployment
Configured for VM deployment to maintain session state and database connections.

## Setup Instructions

### 1. Database Setup
```bash
mysql -u your_username -p your_database_name < complete_database_setup.sql
```

### 2. Configure Database Connection
Update `/backend/config.php`:
```php
$host = "your_host";
$user = "your_username";
$password = "your_password";
$database = "your_database_name";
```

### 3. Access the Application
- **Students**: Login → CALENDAR → Pre-register for events
- **Admins**: Login (admin@nu.edu.ph/admin123) → CALENDAR → Manage events

## URL Structure

### Frontend Pages
- `/` → `/frontend/index.php` - Login/Register
- `/frontend/student_page.php` - Student Dashboard
- `/frontend/admin_page.php` - Admin Dashboard
- `/frontend/student_calendar.php` - Student Calendar
- `/frontend/admin_calendar.php` - Admin Calendar

### Backend APIs
- `/backend/login_register.php` - Authentication
- `/backend/forgot_password.php` - Password reset request
- `/backend/reset_passwordhandler.php` - Password reset handler
- `/backend/get_events.php` - Fetch events
- `/backend/register_event.php` - Student pre-registration
- `/backend/update_university_calendar.php` - Admin calendar sync

## Recent Changes (October 12, 2025)
- ✅ Organized codebase into frontend/ and backend/ folders
- ✅ Created comprehensive database setup scripts
- ✅ Added password_resets table for forgot password functionality
- ✅ Created complete_database_setup.sql for easy installation
- ✅ Updated DATABASE_SETUP_INSTRUCTIONS.md with detailed guide
- ✅ Implemented student calendar with pre-registration
- ✅ Enhanced admin calendar with sync buttons
- ✅ Updated all file paths for new structure
