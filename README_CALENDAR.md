# Google Calendar-Style Event Calendar - Complete Guide

## 🎯 Overview

The National University Events Calendar is now a fully functional, Google Calendar-style calendar system with multiple views, event management, and search capabilities.

## ✨ Key Features

### 📅 Multiple Calendar Views
- **Month View**: Full month grid with events displayed on dates
- **Week View**: Detailed week schedule with hourly time slots  
- **Day View**: Single day view with detailed event information

### 🔍 Search & Filter
- **Event Search**: Real-time search by title, description, or location
- **Type Filters**: Filter by event type (All Events, Webinar, Seminars, Workshop)
- **Quick Access**: Search bar in calendar header for instant filtering

### 📆 Navigation
- **Date Navigation**: Previous/Next buttons to move through dates
- **Today Button**: Jump to current date instantly
- **Mini Calendar**: Side calendar for quick date selection
- **Click to Navigate**: Click any date in month view to see day details

### ✏️ Event Management (Admin Only)
- **Create Events**: Click any time slot on the calendar OR use CREATE button
- **Edit Events**: Click on any event to modify details
- **Delete Events**: Remove events from edit modal
- **Drag & Drop**: Click time slots to create events at specific times

### 🎨 Visual Features
- **Color-Coded Events**: 
  - Events (Blue)
  - Webinar (Orange)
  - Seminars (Red)
  - Workshop (Green)
- **Event Cards**: Display title, time, and location
- **Today Highlighting**: Current date highlighted in all views
- **Responsive Grid**: Auto-adjusting calendar grid

## 🚀 How to Use

### For Admins

#### Viewing Calendar
1. Login as admin
2. Click "CALENDAR" in navigation
3. Use view switcher (Day/Week/Month) to change view
4. Navigate dates with ◀ ▶ buttons or mini calendar

#### Creating Events
**Method 1: CREATE Button**
- Click blue "+ CREATE" button in left sidebar
- Fill in event details
- Click "Save Event"

**Method 2: Click Time Slot**
- In Week or Day view, click any time slot on the calendar
- Modal opens with that time pre-selected
- Fill in event details and save

#### Editing Events
- Click on any event card (in any view)
- Modal opens with event details pre-filled
- Modify details and click "Save Event"
- Or click "Delete Event" to remove

#### Searching Events
- Use search box in calendar header
- Type event title, description, or location
- Calendar updates in real-time

#### Filtering by Type
- Click filter buttons below view switcher
- Choose: All Events, Webinar, Seminars, Workshop
- Calendar shows only selected event types

### For Students

Students can:
- View events in calendar (when implemented for students)
- See upcoming events
- Search events
- **Cannot**: Create, edit, or delete events

## 📱 Views Explained

### Month View
- **Grid Layout**: 7 columns (days) × 6 rows (weeks)
- **Event Display**: Shows up to 3 events per day
- **Overflow**: Shows "+X more" for additional events
- **Click**: Click any date to switch to day view

### Week View
- **Time Slots**: 24-hour grid with hourly increments
- **Event Blocks**: Events displayed at correct times
- **Visual Duration**: Event height shows event duration
- **Quick Create**: Click any time slot to create event

### Day View
- **Detailed View**: Single day with full event details
- **Hour Grid**: Complete 24-hour timeline
- **Event Info**: Shows title, time, and location
- **Best For**: Detailed day planning

## 🎯 Calendar Controls

### Top Navigation
```
[◀] [▶] [Today]  [Current Date/Week/Month]         [🔍 Search Events]
```

### View Switcher
```
[Day] [Week] [Month]  ← Click to change view
```

### Event Filters
```
[All Events] [Webinar] [Seminars] [Workshop]  ← Click to filter
```

### Left Sidebar
- **CREATE Button**: Add new events
- **Mini Calendar**: Quick date navigation
- **Upcoming Events**: List of next 5 events

## 🔐 Security

✅ Admin-only access control
✅ Session-based authentication
✅ Role verification on all event operations
✅ Protected API endpoints
✅ Prepared SQL statements

## 📊 Database

Events are stored with:
- `id`: Unique identifier
- `title`: Event name
- `description`: Event details
- `event_type`: Category (Events/Webinar/Seminars/Workshop)
- `start_date`: Start date & time
- `end_date`: End date & time
- `location`: Event location
- `created_by`: Admin user ID
- `created_at`: Creation timestamp

## 🛠️ Technical Details

### Files
- `admin_calendar.php`: Main calendar page
- `calendar_admin.js`: Calendar logic (500+ lines)
- `calendar_admin.css`: Calendar styling
- `add_event.php`: Create event API
- `edit_event.php`: Update event API
- `delete_event.php`: Remove event API
- `get_events.php`: Fetch events API

### JavaScript Functions
- `renderMonthView()`: Month calendar display
- `renderWeekView()`: Week calendar display
- `renderDayView()`: Day calendar display
- `navigateCalendar()`: Date navigation
- `searchEvents()`: Real-time search
- `createEventAtTime()`: Click-to-create
- `editEvent()`: Event modification
- `deleteEvent()`: Event removal

### Event Display Logic
- Events positioned by start time
- Height calculated from duration
- Color-coded by event type
- Overflow handling for multiple events
- Timezone-aware rendering

## 🎨 Color Scheme

```css
Events:    #4a5bb8 (Blue)
Webinar:   #f39c12 (Orange)
Seminars:  #e74c3c (Red)
Workshop:  #27ae60 (Green)
```

## 📝 Quick Tips

1. **Fast Event Creation**: Click any time slot in Week/Day view
2. **Quick Navigation**: Use mini calendar for date jumping
3. **Filter Focus**: Use filters to see specific event types
4. **Search Smart**: Search works across title, description, and location
5. **View Toggle**: Switch views to see different perspectives

## 🐛 Troubleshooting

**Calendar not loading?**
- Check database connection in `config.php`
- Ensure events table exists (run `database_setup.sql`)
- Verify admin login session

**Events not appearing?**
- Check date range (events must be in database)
- Verify event type filters (try "All Events")
- Clear search box

**Can't create events?**
- Ensure logged in as admin
- Check browser console for errors
- Verify all required fields are filled

## 🚀 Future Enhancements

Potential additions:
- Recurring events
- Event reminders/notifications
- Export to iCal/Google Calendar
- Student calendar view (read-only)
- Event categories and tags
- Drag-and-drop event rescheduling
- Multi-day event spanning
- Calendar sharing

## 📞 Support

For issues or questions:
1. Check browser console for errors
2. Verify database setup
3. Ensure proper admin authentication
4. Review README_SETUP.md for initial setup

---

**Built with ❤️ for National University - Laguna**
