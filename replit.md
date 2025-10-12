# NUEvents - Student Event Management System

## Overview
NUEvents (NUsync) is a PHP-based web application for managing student events at National University. It provides user authentication, event management, and calendar functionality for both students and administrators.

## Project Structure
- **Frontend**: PHP-based UI with HTML/CSS/JavaScript
- **Backend**: PHP with MySQL database (user-managed)
- **Server**: PHP built-in development server on port 5000

## Key Features
- User registration and authentication (students and admins)
- Password reset functionality
- Event creation, editing, and deletion (admin)
- Event calendar view
- User profile management
- Role-based access control

## Database Tables
The application uses MySQL with the following tables:
- `student`: User accounts (id, name, email, password, role)
- `events`: Event information (id, title, description, event_type, start_date, end_date, location, created_by)
- `password_resets`: Password reset tokens (user_id, token, expires_at)

**Note**: Database setup and management is handled externally by the project owner.

## File Structure
```
├── index.php              # Login/Register page
├── login_register.php     # Authentication handler
├── student_page.php       # Student dashboard
├── admin_page.php         # Admin dashboard
├── calendar.php           # Student calendar view
├── admin_calendar.php     # Admin calendar view
├── MyProfilePage.php      # User profile page
├── forgot_password.php    # Password reset request
├── reset_password.php     # Password reset form
├── reset_passwordhandler.php # Password reset handler
├── add_event.php          # Add event handler
├── edit_event.php         # Edit event handler
├── delete_event.php       # Delete event handler
├── get_events.php         # Event data API
├── config.php             # Database configuration
├── assets/                # Images and resources
└── *.css, *.js           # Stylesheets and scripts
```

## Running the Application
The PHP server is configured to run on port 5000 with the command:
```bash
php -S 0.0.0.0:5000
```

## Deployment
Configured for VM deployment to maintain session state and database connections.

## Recent Changes (October 12, 2025)
- Set up PHP development environment in Replit
- Configured PHP server workflow on port 5000
- Configured deployment settings for production
- Preserved original MySQL database configuration for external management
