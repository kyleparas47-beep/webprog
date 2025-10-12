# Database Setup Instructions

## Overview
The NUEvents application requires 4 MySQL database tables to function properly. This guide will help you set up your MySQL database using XAMPP.

---

## Database Configuration

After creating the tables, make sure your database connection settings in `config.php` are correct:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "student_db";
```

---

## Quick Setup - Import SQL Files in Order

The SQL files are numbered to show the correct import order. Import them in sequence:

### Step 1: Create Database (if not exists)
```sql
CREATE DATABASE IF NOT EXISTS student_db;
USE student_db;
```

### Step 2: Import Tables in Order

**Import these files in XAMPP's phpMyAdmin or MySQL command line:**

1. **1_student_table.sql** - User accounts (Required FIRST)
   - Creates the student table for all user accounts
   - Includes default admin account (email: admin@nu.edu.ph, password: admin123)
   - ⚠️ Change admin password after first login!

2. **2_events_table.sql** - Event information
   - Creates the events table
   - Links events to the user who created them

3. **3_password_resets_table.sql** - Password reset functionality
   - Creates the password_resets table
   - Enables "Forgot Password" feature
   - Stores reset tokens with 1-hour expiration

4. **4_event_registrations_table.sql** - Student pre-registration
   - Creates the event_registrations table
   - Enables students to pre-register for events
   - Prevents duplicate registrations

---

## Method 1: Using phpMyAdmin (Easiest)

1. Open XAMPP Control Panel and start **MySQL**
2. Click **Admin** next to MySQL (opens phpMyAdmin)
3. Click on **student_db** database (or create it first)
4. Go to the **Import** tab
5. Click **Choose File** and select **1_student_table.sql**
6. Click **Go** at the bottom
7. Repeat steps 5-6 for the remaining files in order:
   - 2_events_table.sql
   - 3_password_resets_table.sql
   - 4_event_registrations_table.sql

---

## Method 2: Using MySQL Command Line

```bash
# Navigate to your project directory
cd /path/to/your/project

# Import each file in order
mysql -u root -p student_db < 1_student_table.sql
mysql -u root -p student_db < 2_events_table.sql
mysql -u root -p student_db < 3_password_resets_table.sql
mysql -u root -p student_db < 4_event_registrations_table.sql
```

---

## Method 3: Using SQL Tab in phpMyAdmin

1. Open phpMyAdmin
2. Select your **student_db** database
3. Go to the **SQL** tab
4. Open each .sql file in a text editor
5. Copy the contents and paste into the SQL tab
6. Click **Go**
7. Repeat for each file in order

---

## Verify Installation

After importing all tables, verify they were created successfully:

```sql
-- Show all tables
SHOW TABLES;

-- Should show:
-- - student
-- - events
-- - password_resets
-- - event_registrations

-- Check each table structure
DESCRIBE student;
DESCRIBE events;
DESCRIBE password_resets;
DESCRIBE event_registrations;

-- Verify admin account exists
SELECT * FROM student WHERE role = 'admin';
```

---

## Default Admin Account

**Email:** admin@nu.edu.ph  
**Password:** admin123

⚠️ **IMPORTANT:** Change this password immediately after first login for security!

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

## Troubleshooting

### Error: "Table already exists"
- This is safe to ignore. The scripts use `IF NOT EXISTS` to prevent errors.

### Error: "Foreign key constraint fails"
- Make sure you import the files in the correct order (1, 2, 3, 4)
- The `student` table must be created first
- The `events` table must exist before importing `event_registrations`

### Error: "Access denied"
- Check your MySQL username and password in `config.php`
- Ensure your MySQL user has CREATE, ALTER, INSERT, UPDATE, DELETE privileges

### Error: "Database doesn't exist"
- Create the database first: `CREATE DATABASE student_db;`
- Then select it: `USE student_db;`
- Then import the tables

### Forgot Password Not Working
1. Verify `password_resets` table exists:
   ```sql
   SELECT * FROM password_resets;
   ```
2. Check table structure:
   ```sql
   DESCRIBE password_resets;
   ```
3. Ensure all columns exist: `id`, `user_id`, `token`, `expires_at`, `created_at`

---

## Import Order Summary

✅ **Correct Order:**
1. 1_student_table.sql
2. 2_events_table.sql
3. 3_password_resets_table.sql
4. 4_event_registrations_table.sql

❌ **Wrong:** Importing in random order will cause foreign key errors!

---

## Need Help?

If you encounter any issues:
1. Check your database connection in `config.php`
2. Verify your MySQL user has proper permissions
3. Ensure all prerequisite tables exist (especially `student` table)
4. Check MySQL error logs for detailed error messages
5. Make sure you imported the tables in the correct order (1 → 2 → 3 → 4)
