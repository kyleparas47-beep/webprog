# National University Events Calendar - Setup Instructions

## Database Setup (XAMPP)

### Step 1: Create Events Table
1. Open **phpMyAdmin** in your XAMPP
2. Select your database `student_db`
3. Go to the **SQL** tab
4. Copy and paste the contents from `database_setup.sql`
5. Click **Go** to execute

The table structure includes:
- `id` - Auto-increment primary key
- `title` - Event title
- `description` - Event description
- `event_type` - Type (Events, Webinar, Seminars, Workshop)
- `start_date` - Event start date and time
- `end_date` - Event end date and time
- `location` - Event location
- `created_by` - User ID of creator (foreign key to student table)
- `created_at` - Timestamp of creation

## Pages Overview

### 1. **Admin Page** (`admin_page.php`)
- Scrolling marquee banner with "Events Calendar 🎉 National University"
- Breadcrumb navigation (HOME > EVENTS)
- "See Current Events" button
- "+ add events >" link (goes to calendar)
- Two tilted photo cards

### 2. **Student Page** (`student_page.php`)
- Same design as admin page
- Only "See Current Events" button (no add events)
- Students cannot access the calendar management

### 3. **Admin Calendar** (`admin_calendar.php`)
- **Left Sidebar:**
  - CREATE button to add new events
  - Mini calendar
  - Meet with section (search people)
  - Time insights
  - Upcoming events list

- **Main Area:**
  - University Schedule Week view
  - Week calendar grid with time slots
  - Event type filters (Events, Webinar, Seminars, Workshop)
  - Week view controls

- **Right Sidebar:**
  - Search events
  - View University Calendar button

### Event Management (Admin Only)
- **Add Event**: Click "+ CREATE" or "+ add events >" link
- **Edit Event**: Click on an event in the upcoming events list
- **Delete Event**: Click delete button in the edit modal

## Backend Files

- `add_event.php` - Creates new events
- `edit_event.php` - Updates existing events
- `delete_event.php` - Removes events
- `get_events.php` - Fetches all events

## Security Features

✅ Admin-only access to calendar and event management
✅ Session-based authentication
✅ Role checking (admin vs student)
✅ SQL prepared statements to prevent injection

## File Structure

```
├── admin_page.php           # Admin home page
├── student_page.php         # Student home page
├── admin_calendar.php       # Calendar with event management (admin only)
├── calendar_admin.css       # Calendar page styles
├── calendar_admin.js        # Calendar functionality
├── student.css              # Main styles + marquee animation
├── add_event.php            # Add event backend
├── edit_event.php           # Edit event backend
├── delete_event.php         # Delete event backend
├── get_events.php           # Fetch events backend
└── database_setup.sql       # Database schema
```

## Testing

1. **Login as Admin** to access calendar and event management
2. **Login as Student** to view events only
3. **Create Event**: Use the CREATE button or "+ add events" link
4. **Edit Event**: Click on any event in the upcoming events list
5. **Delete Event**: Use the delete button in the edit modal

## Notes

- The database connection uses your existing `config.php` with XAMPP MySQL settings
- Events are displayed in chronological order (soonest first)
- All event operations require admin authentication
- The marquee banner scrolls continuously across the top of the page
