# NUEvents - Event Management System

## Overview
NUEvents is a PHP-based event management system for National University. It allows students to view and register for events, and administrators to create and manage events through a calendar interface.

## Project Architecture

### Frontend
- **Location**: `/frontend/` directory
- **Entry Point**: `index.php` (login/registration page)
- **Main Pages**:
  - `index.php` - Login, Register, and Forgot Password forms
  - `student_page.php` - Student dashboard
  - `student_calendar.php` - Student event calendar view
  - `admin_page.php` - Admin dashboard
  - `admin_calendar.php` - Admin calendar with event management
  - `MyProfilePage.php` - User profile management
- **Assets**: Images and logos stored in `/frontend/assets/`
- **Styling**: CSS files (style.css, student.css, calendar_admin.css, etc.)
- **JavaScript**: Interactive functionality (script.js, scriptcal.js, calendar_functions.js, etc.)

### Backend
- **Location**: `/backend/` directory
- **Database Configuration**: `config.php` (connects to MySQL database `student_db`)
- **API Endpoints**:
  - `login_register.php` - User authentication and registration
  - `forgot_password.php` - Password reset request
  - `reset_passwordhandler.php` - Password reset handler
  - `get_events.php` - Fetch events from database
  - `add_event.php` - Create new event
  - `edit_event.php` - Update existing event
  - `delete_event.php` - Remove event
  - `register_event.php` - Student event registration
  - `get_registered_events.php` - Fetch student's registered events
  - `update_university_calendar.php` - Sync university calendar

### Database
- **Database Name**: `student_db`
- **Host**: localhost (via XAMPP)
- **User**: root
- **Password**: (empty)
- **Tables**:
  - `student` - User accounts (students and admins)
  - `events` - Event information
  - `password_resets` - Password reset tokens
  - `event_registrations` - Student event registrations

**Setup Instructions**: See `DATABASE_SETUP_INSTRUCTIONS.md` for detailed database setup via XAMPP

## Technology Stack
- **Language**: PHP 8.2
- **Database**: MySQL (via XAMPP)
- **Frontend**: HTML, CSS, JavaScript (Vanilla)
- **Sessions**: PHP sessions for authentication

## User Roles
1. **Student**: Can view events, register for events, manage profile
2. **Admin**: Can create/edit/delete events, manage calendar (default admin: admin@nu.edu.ph / admin123)

## Recent Changes
- **Project imported** to Replit environment (Date: October 12, 2025)
- PHP 8.2 installed and configured
- PHP built-in development server configured on port 5000

## User Preferences
- Database management handled externally via XAMPP
- Backend/database configuration should not be modified in Replit

## Running the Application
The application runs on PHP's built-in development server:
- **Server**: PHP 8.2
- **Port**: 5000
- **Access**: Via Replit webview

**Note**: The database must be running in XAMPP separately for full functionality.
