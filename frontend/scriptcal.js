let currentDate = new Date();
let currentWeekStart = getWeekStart(new Date());
let events = [];
let selectedFilter = 'all';

document.addEventListener('DOMContentLoaded', function() {
    initCalendar();
    loadEvents();
    setupEventListeners();
});

function setupEventListeners() {
    document.getElementById('createEventBtn').addEventListener('click', () => openModal());
    document.getElementById('closeModal').addEventListener('click', closeModal);
    document.getElementById('cancelBtn').addEventListener('click', closeModal);
    document.getElementById('eventForm').addEventListener('submit', handleFormSubmit);
    
    document.getElementById('prevMonth').addEventListener('click', () => changeMonth(-1));
    document.getElementById('nextMonth').addEventListener('click', () => changeMonth(1));
    document.getElementById('prevWeek').addEventListener('click', () => changeWeek(-1));
    document.getElementById('nextWeek').addEventListener('click', () => changeWeek(1));
    
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedFilter = this.dataset.filter;
            renderSchedule();
            renderUpcomingEvents();
        });
    });
    
    document.querySelector('.modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
}

function initCalendar() {
    renderMiniCalendar();
    renderSchedule();
    updateTimezone();
}

function renderMiniCalendar() {
    const calendar = document.getElementById('miniCalendar');
    const monthYear = document.getElementById('miniMonthYear');
    
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    monthYear.textContent = currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
    
    calendar.innerHTML = '';
    
    const daysOfWeek = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
    daysOfWeek.forEach(day => {
        const dayHeader = document.createElement('div');
        dayHeader.className = 'calendar-day-header';
        dayHeader.textContent = day;
        calendar.appendChild(dayHeader);
    });
    
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();
    
    for (let i = firstDay - 1; i >= 0; i--) {
        const day = document.createElement('div');
        day.className = 'calendar-day other-month';
        day.textContent = daysInPrevMonth - i;
        calendar.appendChild(day);
    }
    
    const today = new Date();
    for (let i = 1; i <= daysInMonth; i++) {
        const day = document.createElement('div');
        day.className = 'calendar-day';
        day.textContent = i;
        
        if (year === today.getFullYear() && month === today.getMonth() && i === today.getDate()) {
            day.classList.add('today');
        }
        
        day.addEventListener('click', function() {
            const selectedDate = new Date(year, month, i);
            currentWeekStart = getWeekStart(selectedDate);
            renderSchedule();
        });
        
        calendar.appendChild(day);
    }
    
    const totalCells = calendar.children.length - 7;
    const remainingCells = 42 - totalCells;
    for (let i = 1; i <= remainingCells; i++) {
        const day = document.createElement('div');
        day.className = 'calendar-day other-month';
        day.textContent = i;
        calendar.appendChild(day);
    }
}

function renderSchedule() {
    renderTimeColumn();
    renderDayColumns();
    updateWeekRange();
}

function renderTimeColumn() {
    const timeColumn = document.getElementById('timeColumn');
    timeColumn.innerHTML = '<div style="height: 60px;"></div>';
    
    for (let hour = 9; hour <= 15; hour++) {
        const timeSlot = document.createElement('div');
        timeSlot.className = 'time-slot';
        timeSlot.textContent = `${hour.toString().padStart(2, '0')}:00`;
        timeColumn.appendChild(timeSlot);
    }
}

function renderDayColumns() {
    const daysContainer = document.getElementById('daysContainer');
    daysContainer.innerHTML = '';
    
    const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    
    for (let i = 0; i < 6; i++) {
        const date = new Date(currentWeekStart);
        date.setDate(date.getDate() + i);
        
        const dayColumn = document.createElement('div');
        dayColumn.className = 'day-column';
        
        const dayHeader = document.createElement('div');
        dayHeader.className = 'day-header';
        dayHeader.innerHTML = `
            <div class="day-name">${days[i]}</div>
            <div class="day-date">${date.getDate().toString().padStart(2, '0')}</div>
        `;
        dayColumn.appendChild(dayHeader);
        
        const daySlots = document.createElement('div');
        daySlots.className = 'day-slots';
        
        for (let hour = 9; hour <= 15; hour++) {
            const hourSlot = document.createElement('div');
            hourSlot.className = 'hour-slot';
            hourSlot.dataset.date = date.toISOString().split('T')[0];
            hourSlot.dataset.hour = hour;
            
            hourSlot.addEventListener('click', function() {
                openModal(this.dataset.date, this.dataset.hour);
            });
            
            daySlots.appendChild(hourSlot);
        }
        
        renderEventsForDay(date, daySlots);
        
        dayColumn.appendChild(daySlots);
        daysContainer.appendChild(dayColumn);
    }
}

function renderEventsForDay(date, daySlots) {
    const dateStr = date.toISOString().split('T')[0];
    const dayEvents = events.filter(event => {
        if (selectedFilter !== 'all' && event.type !== selectedFilter) {
            return false;
        }
        return event.date === dateStr;
    });
    
    dayEvents.forEach(event => {
        const [startHour, startMinute] = event.startTime.split(':').map(Number);
        const [endHour, endMinute] = event.endTime.split(':').map(Number);
        
        if (startHour < 9 || startHour > 15) return;
        
        const startOffset = ((startHour - 9) * 60 + startMinute) / 60;
        const duration = ((endHour - startHour) * 60 + (endMinute - startMinute)) / 60;
        
        const eventEl = document.createElement('div');
        eventEl.className = 'schedule-event';
        eventEl.style.top = `${startOffset * 60}px`;
        eventEl.style.height = `${duration * 60}px`;
        eventEl.innerHTML = `
            <div class="schedule-event-title">${event.title}</div>
            <div class="schedule-event-time">${event.startTime} - ${event.endTime}</div>
        `;
        
        eventEl.addEventListener('click', function(e) {
            e.stopPropagation();
            if (confirm('Delete this event?')) {
                deleteEvent(event.id);
            }
        });
        
        daySlots.appendChild(eventEl);
    });
}

function renderUpcomingEvents() {
    const upcomingList = document.getElementById('upcomingEventsList');
    
    const filteredEvents = events.filter(event => {
        if (selectedFilter !== 'all' && event.type !== selectedFilter) {
            return false;
        }
        const eventDate = new Date(event.date + 'T' + event.startTime);
        return eventDate >= new Date();
    }).sort((a, b) => {
        const dateA = new Date(a.date + 'T' + a.startTime);
        const dateB = new Date(b.date + 'T' + b.startTime);
        return dateA - dateB;
    }).slice(0, 5);
    
    if (filteredEvents.length === 0) {
        upcomingList.innerHTML = '<p style="color: #999; font-size: 12px;">No upcoming events</p>';
        return;
    }
    
    upcomingList.innerHTML = filteredEvents.map(event => {
        const eventDate = new Date(event.date);
        const displayDate = eventDate.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
        
        return `
            <div class="event-item">
                <div class="event-icon"></div>
                <div class="event-info">
                    <div class="event-title">${event.title}</div>
                    <div class="event-time">${displayDate} • ${event.startTime}</div>
                </div>
                <button class="event-delete" onclick="deleteEvent(${event.id})">×</button>
            </div>
        `;
    }).join('');
}

function updateWeekRange() {
    const weekEnd = new Date(currentWeekStart);
    weekEnd.setDate(weekEnd.getDate() + 5);
    
    const startStr = currentWeekStart.toLocaleDateString('en-US', { day: '2-digit', month: 'long' });
    const endStr = weekEnd.toLocaleDateString('en-US', { day: '2-digit', month: 'long' });
    
    document.getElementById('weekDateRange').textContent = `${startStr.split(' ')[1]} - ${endStr}`;
    document.getElementById('insightsDateRange').textContent = `${currentWeekStart.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`;
}

function updateTimezone() {
    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    const offset = new Date().toTimeString().match(/GMT([+-]\d{2}:\d{2})/)?.[1] || '';
    document.getElementById('timezoneDisplay').textContent = `(GMT${offset}) Public Time`;
}

function changeMonth(delta) {
    currentDate.setMonth(currentDate.getMonth() + delta);
    renderMiniCalendar();
}

function changeWeek(delta) {
    currentWeekStart.setDate(currentWeekStart.getDate() + (delta * 7));
    renderSchedule();
}

function getWeekStart(date) {
    const d = new Date(date);
    const day = d.getDay();
    const diff = d.getDate() - day + (day === 0 ? -6 : 1);
    return new Date(d.setDate(diff));
}

function openModal(date = null, hour = null) {
    const modal = document.getElementById('eventModal');
    const form = document.getElementById('eventForm');
    
    form.reset();
    document.getElementById('eventId').value = '';
    document.getElementById('modalTitle').textContent = 'Create Event';
    
    if (date) {
        document.getElementById('eventDate').value = date;
        if (hour) {
            document.getElementById('eventStartTime').value = `${hour.toString().padStart(2, '0')}:00`;
            document.getElementById('eventEndTime').value = `${(parseInt(hour) + 1).toString().padStart(2, '0')}:00`;
        }
    } else {
        const today = new Date();
        document.getElementById('eventDate').value = today.toISOString().split('T')[0];
    }
    
    modal.classList.add('active');
}

function closeModal() {
    document.getElementById('eventModal').classList.remove('active');
}

async function handleFormSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const eventData = {
        id: formData.get('eventId') || Date.now(),
        title: formData.get('title'),
        type: formData.get('type'),
        date: formData.get('date'),
        startTime: formData.get('startTime'),
        endTime: formData.get('endTime'),
        description: formData.get('description'),
        location: formData.get('location')
    };
    
    try {
        const response = await fetch('/backend/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'add',
                event: eventData
            })
        });
        
        const result = await response.json();
        if (result.success) {
            closeModal();
            loadEvents();
        } else {
            alert('Error saving event: ' + result.message);
        }
    } catch (error) {
        alert('Error saving event: ' + error.message);
    }
}

async function loadEvents() {
    try {
        const response = await fetch('/backend/api.php?action=get');
        const result = await response.json();
        
        if (result.success) {
            events = result.events;
            renderSchedule();
            renderUpcomingEvents();
        }
    } catch (error) {
        console.error('Error loading events:', error);
    }
}

async function deleteEvent(eventId) {
    if (!confirm('Are you sure you want to delete this event?')) {
        return;
    }
    
    try {
        const response = await fetch('/backend/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'delete',
                id: eventId
            })
        });
        
        const result = await response.json();
        if (result.success) {
            loadEvents();
        } else {
            alert('Error deleting event: ' + result.message);
        }
    } catch (error) {
        alert('Error deleting event: ' + error.message);
    }
}
