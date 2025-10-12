# 📋 DATABASE IMPORT ORDER

⚠️ **IMPORTANT:** You MUST import these files in the exact order shown below!

## Step-by-Step Import Instructions

### Step 1: Open phpMyAdmin
1. Start XAMPP Control Panel
2. Start MySQL
3. Click "Admin" button next to MySQL

### Step 2: Select/Create Database
```sql
CREATE DATABASE IF NOT EXISTS student_db;
USE student_db;
```

### Step 3: Import Files IN THIS ORDER

#### 📄 1. First - Import `1_student_table.sql`
- This creates the users table
- **Must be imported FIRST** (other tables depend on it)
- ✅ Import this file and wait for success message

#### 📄 2. Second - Import `2_events_table.sql`  
- This creates the events table
- Depends on: student table (foreign key to student.id)
- ✅ Import this file after student table succeeds

#### 📄 3. Third - Import `3_password_resets_table.sql`
- This creates the password resets table
- Depends on: student table (foreign key to student.id)
- ✅ Import this file after student table exists

#### 📄 4. Fourth - Import `4_event_registrations_table.sql`
- This creates the event registrations table
- Depends on: BOTH student AND events tables
- ✅ Import this file LAST (after all others succeed)

---

## ❌ Common Mistakes

**DON'T do this:**
- ❌ Import files in random order
- ❌ Import file 4 before files 1, 2, 3
- ❌ Skip any files

**DO this:**
- ✅ Import in order: 1 → 2 → 3 → 4
- ✅ Wait for each import to succeed before moving to next
- ✅ Check for success message after each import

---

## 🔍 How to Fix Your Current Error

You got the error because you tried to import `4_event_registrations_table.sql` but the `student` and `events` tables don't exist yet.

### Solution:
1. **Start fresh** - Drop the database and recreate it:
   ```sql
   DROP DATABASE IF EXISTS student_db;
   CREATE DATABASE student_db;
   USE student_db;
   ```

2. **Import in correct order:**
   - Import `1_student_table.sql` ✅
   - Import `2_events_table.sql` ✅
   - Import `3_password_resets_table.sql` ✅
   - Import `4_event_registrations_table.sql` ✅

---

## ✅ Verify Success

After importing all 4 files, run this to verify:

```sql
-- Show all tables
SHOW TABLES;

-- Should show 4 tables:
-- - student
-- - events  
-- - password_resets
-- - event_registrations

-- Verify admin account exists
SELECT * FROM student WHERE role = 'admin';
```

---

## 📞 Need Help?

If you still get errors:
1. Make sure MySQL is running in XAMPP
2. Use database: `student_db`
3. Import files one at a time in order 1→2→3→4
4. Check each import succeeds before continuing
