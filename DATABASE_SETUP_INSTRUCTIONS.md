# Database Setup Instructions

## Important: Database Table Required for Pre-Registration

To enable the student pre-registration feature, you need to create the `event_registrations` table in your MySQL database.

## Step-by-Step Instructions

### Option 1: Using MySQL Command Line

1. Open your MySQL terminal or command prompt
2. Navigate to your project directory
3. Run the following command:

```bash
mysql -u your_username -p your_database_name < event_registrations_table.sql
```

Replace:
- `your_username` with your MySQL username
- `your_database_name` with your database name

### Option 2: Using phpMyAdmin or MySQL Workbench

1. Open your database management tool
2. Select your database (`student_db` by default)
3. Go to the SQL tab
4. Copy and paste the contents of `event_registrations_table.sql`
5. Click "Execute" or "Run"

### Option 3: Manual SQL

Run this SQL command in your database:

```sql
CREATE TABLE IF NOT EXISTS `event_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_registration` (`student_id`, `event_id`),
  KEY `student_id` (`student_id`),
  KEY `event_id` (`event_id`),
  FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Verify Installation

After running the SQL script, verify the table was created:

```sql
SHOW TABLES LIKE 'event_registrations';
DESCRIBE event_registrations;
```

## How It Works

- **Students** can pre-register for events through the student calendar
- Each registration is unique (one student can only register once per event)
- When an event is deleted, all related registrations are automatically removed
- When a student account is deleted, all their registrations are automatically removed

## Troubleshooting

If you encounter errors:

1. **Foreign Key Constraint Error**: Make sure the `student` and `events` tables exist first
2. **Table Already Exists**: The script uses `IF NOT EXISTS`, so it's safe to run multiple times
3. **Permission Error**: Ensure your MySQL user has CREATE and ALTER privileges

## Need Help?

If you have any issues setting up the database table, please check:
- Your database connection in `config.php`
- Your MySQL user permissions
- That the `student` and `events` tables exist
