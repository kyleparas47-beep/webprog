# Database Setup Instructions

## Overview
The NUEvents application requires 4 main database tables to function properly. This guide will help you set up your MySQL database.

## Quick Setup (Recommended)

### Option 1: Complete Setup (All Tables at Once)
Run this command to create all required tables:

```bash
mysql -u your_username -p your_database_name < complete_database_setup.sql
```

This creates:
- ✅ `student` table (user accounts)
- ✅ `events` table (event information)
- ✅ `password_resets` table (forgot password functionality)
- ✅ `event_registrations` table (student pre-registration)

---

## Individual Table Setup

If you need to add tables one by one, use these commands:

### 1. Student/User Table (Required First)
```bash
mysql -u your_username -p your_database_name < student_table.sql
```

**What it does:**
- Creates the `student` table for user accounts
- Adds a default admin account (email: admin@nu.edu.ph, password: admin123)
- ⚠️ **Change the admin password after first login!**

### 2. Events Table
```bash
mysql -u your_username -p your_database_name < database_setup.sql
```

**What it does:**
- Creates the `events` table for storing event information
- Links events to the user who created them

### 3. Password Resets Table (For Forgot Password)
```bash
mysql -u your_username -p your_database_name < password_resets_table.sql
```

**What it does:**
- Creates the `password_resets` table
- Enables "Forgot Password" functionality
- Stores reset tokens with 1-hour expiration

### 4. Event Registrations Table (For Student Pre-Registration)
```bash
mysql -u your_username -p your_database_name < event_registrations_table.sql
```

**What it does:**
- Creates the `event_registrations` table
- Enables students to pre-register for events
- Prevents duplicate registrations

---

## Using phpMyAdmin or MySQL Workbench

1. Open your database management tool
2. Select your database (`student_db` by default)
3. Go to the SQL tab
4. Copy and paste the contents of `complete_database_setup.sql`
5. Click "Execute" or "Run"

---

## Database Configuration

After creating the tables, update `/backend/config.php` with your credentials:

```php
$host = "localhost";           // Your MySQL host
$user = "root";               // Your MySQL username
$password = "your_password";  // Your MySQL password
$database = "student_db";     // Your database name
```

---

## How Each Feature Works

### 🔐 Forgot Password Flow
1. User enters email on login page
2. System generates a unique reset token (valid for 1 hour)
3. Token is stored in `password_resets` table
4. User receives email with reset link
5. User clicks link and sets new password
6. Token is deleted after successful reset

### 📅 Event Registration Flow
1. Admin creates event → stored in `events` table
2. Student views event in calendar
3. Student clicks "Pre-Register"
4. Registration is stored in `event_registrations` table
5. Student sees event in "My Registered Events" panel

---

## Verify Installation

Run these commands to check if tables were created:

```sql
-- Show all tables
SHOW TABLES;

-- Check student table
DESCRIBE student;

-- Check password_resets table
DESCRIBE password_resets;

-- Check events table
DESCRIBE events;

-- Check event_registrations table
DESCRIBE event_registrations;
```

---

## Troubleshooting

### Error: "Table already exists"
- This is safe to ignore. The scripts use `IF NOT EXISTS` to prevent errors.

### Error: "Foreign key constraint fails"
- Make sure you create the `student` table first
- Then create `events` and `password_resets` tables
- Finally, create `event_registrations` table

### Error: "Access denied"
- Check your MySQL username and password in `/backend/config.php`
- Ensure your MySQL user has CREATE, ALTER, INSERT, UPDATE, DELETE privileges

### Forgot Password Not Working
1. Verify `password_resets` table exists:
   ```sql
   SELECT * FROM password_resets;
   ```
2. Check table structure:
   ```sql
   DESCRIBE password_resets;
   ```
3. Ensure columns: `id`, `user_id`, `token`, `expires_at`, `created_at`

---

## Default Admin Account

**Email:** admin@nu.edu.ph  
**Password:** admin123

⚠️ **IMPORTANT:** Change this password immediately after first login for security!

---

## Need Help?

If you encounter any issues:
1. Check your database connection in `/backend/config.php`
2. Verify your MySQL user has proper permissions
3. Ensure all prerequisite tables exist (especially `student` table)
4. Check MySQL error logs for detailed error messages
