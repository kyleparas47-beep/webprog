# NUEvents - Student Event Management System

## Overview
NUEvents (NUsync) is a PHP-based web application for managing student events at National University. It provides user authentication, event management, calendar functionality, and student pre-registration for both students and administrators.

## Project Structure
- **Frontend**: PHP-based UI with HTML/CSS/JavaScript
- **Backend**: PHP with MySQL database (user-managed)
- **Server**: PHP built-in development server on port 5000

## Key Features
- User registration and authentication (students and admins)
- Password reset functionality
- Event creation, editing, and deletion (admin)
- **Student Calendar**: View-only calendar with pre-registration functionality
- **Admin Calendar**: Full calendar management with create, edit, delete capabilities
- Event pre-registration system (students only)
- Calendar synchronization between admin and student views
- Role-based access control

## Database Tables
The application uses MySQL with the following tables:

### Core Tables
- `student`: User accounts (id, name, email, password, role)
- `events`: Event information (id, title, description, event_type, start_date, end_date, location, created_by)
- `password_resets`: Password reset tokens (user_id, token, expires_at)

### New Table (Required)
- `event_registrations`: Student event pre-registrations (id, student_id, event_id, registered_at)

**Important**: Run the `event_registrations_table.sql` script in your MySQL database to create the registrations table.

**Note**: Database setup and management is handled externally by the project owner.

## File Structure
```
├── index.php                      # Login/Register page
├── login_register.php             # Authentication handler
├── student_page.php               # Student dashboard
├── admin_page.php                 # Admin dashboard
├── student_calendar.php           # Student calendar with pre-registration (NEW)
├── calendar.php                   # Legacy calendar page
├── admin_calendar.php             # Admin calendar with management tools
├── MyProfilePage.php              # User profile page
├── forgot_password.php            # Password reset request
├── reset_password.php             # Password reset form
├── reset_passwordhandler.php      # Password reset handler
├── add_event.php                  # Add event handler
├── edit_event.php                 # Edit event handler
├── delete_event.php               # Delete event handler
├── get_events.php                 # Event data API
├── register_event.php             # Student registration handler (NEW)
├── get_registered_events.php      # Get student registrations (NEW)
├── update_university_calendar.php # Admin calendar update handler (NEW)
├── config.php                     # Database configuration
├── calendar_admin.js              # Admin calendar JavaScript
├── calendar_functions.js          # Shared calendar rendering functions (NEW)
├── event_registrations_table.sql  # SQL script for registrations table (NEW)
├── assets/                        # Images and resources
└── *.css, *.js                    # Stylesheets and scripts
```

## Calendar System

### Student Calendar (`student_calendar.php`)
- **View Events**: Students can view all events added by admins
- **Pre-Registration**: Click on any event to pre-register
- **Success Popup**: Shows confirmation message upon successful registration
- **My Registered Events**: Right-side panel showing all pre-registered events
- **Search & Filter**: Search events by keyword, filter by type (Webinar, Seminars, Workshop)
- **Multiple Views**: Day, Week, and Month calendar views

### Admin Calendar (`admin_calendar.php`)
- **Full Management**: Create, edit, and delete events
- **Save Updates Button**: Saves current calendar state (bottom right)
- **Update University Calendar Button**: Publishes events to student calendar (bottom right)
- **Event Types**: Events, Webinar, Seminars, Workshop
- **Calendar Views**: Day, Week, and Month views

## Running the Application
The PHP server is configured to run on port 5000 with the command:
```bash
php -S 0.0.0.0:5000
```

## Deployment
Configured for VM deployment to maintain session state and database connections.

## Setup Instructions

### 1. Database Setup
Run the following SQL script in your MySQL database:
```bash
mysql -u your_username -p your_database < event_registrations_table.sql
```

### 2. Configure Database Connection
Update `config.php` with your MySQL credentials:
```php
$host = "your_host";
$user = "your_username";
$password = "your_password";
$database = "your_database_name";
```

### 3. Access the Application
- **Students**: Login → Navigate to CALENDAR → Pre-register for events
- **Admins**: Login → Navigate to CALENDAR → Create/manage events → Click "UPDATE UNIVERSITY CALENDAR" to publish

## Recent Changes (October 12, 2025)
- ✅ Set up PHP development environment in Replit
- ✅ Configured PHP server workflow on port 5000
- ✅ Configured deployment settings for production
- ✅ Created student calendar page with pre-registration functionality
- ✅ Added event registration system for students
- ✅ Added registered events panel on student calendar
- ✅ Enhanced admin calendar with "Save Updates" and "Update University Calendar" buttons
- ✅ Implemented calendar synchronization between admin and student views
- ✅ Created event_registrations database table structure

## User Preferences
- Database management handled externally by project owner
- UI and backend focused development
