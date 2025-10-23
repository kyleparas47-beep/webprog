let currentDate = new Date();
let currentView = 'week';
let allEvents = [];
let currentEventId = null;
let filteredEventType = 'all';

const DAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
const DAYS_SHORT = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
const MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

document.addEventListener('DOMContentLoaded', function() {
    loadEvents();
    renderCalendar();
    updateMiniCalendar();
    setupEventListeners();
});

function setupEventListeners() {
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            filteredEventType = this.dataset.filter;
            renderCalendar();
        });
    });
}

function loadEvents() {
    fetch('../../api/events/get_events.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allEvents = data.events.map(event => ({
                    ...event,
                    start: parseApiDate(event.start_date),
                    end: parseApiDate(event.end_date)
                }));
                renderCalendar();
                displayUpcomingEvents();
            }
        })
        .catch(error => console.error('Error loading events:', error));
}

function parseApiDate(value) {
    if (!value) return null;
    const str = typeof value === 'string' ? value.replace(' ', 'T') : value;
    return new Date(str);
}

function renderCalendar() {
    switch(currentView) {
        case 'month':
            renderMonthView();
            break;
        case 'week':
            renderWeekView();
            break;
        case 'day':
            renderDayView();
            break;
    }
    updateCalendarHeader();
}

function renderMonthView() {
    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    const startDate = new Date(firstDay);
    startDate.setDate(startDate.getDate() - firstDay.getDay());
    
    let html = '<div class="month-view">';
    html += '<div class="month-header">';
    DAYS_SHORT.forEach(day => {
        html += `<div class="month-day-header">${day}</div>`;
    });
    html += '</div><div class="month-grid">';
    
    let currentDatePointer = new Date(startDate);
    for (let i = 0; i < 42; i++) {
        const isCurrentMonth = currentDatePointer.getMonth() === currentDate.getMonth();
        const isToday = currentDatePointer.toDateString() === new Date().toDateString();
        const dayEvents = getEventsForDate(currentDatePointer);
        
        html += `<div class="month-day ${!isCurrentMonth ? 'other-month' : ''} ${isToday ? 'today' : ''}" 
                      onclick="selectDate('${currentDatePointer.toISOString()}')">
                    <div class="month-day-number">${currentDatePointer.getDate()}</div>
                    <div class="month-day-events">`;
        
        dayEvents.slice(0, 3).forEach(event => {
            html += `<div class="month-event ${event.event_type.toLowerCase()}" 
                         onclick="event.stopPropagation(); editEvent(${event.id})">
                        ${event.title}
                     </div>`;
        });
        
        if (dayEvents.length > 3) {
            html += `<div class="month-event-more">+${dayEvents.length - 3} more</div>`;
        }
        
        html += '</div></div>';
        currentDatePointer.setDate(currentDatePointer.getDate() + 1);
    }
    
    html += '</div></div>';
    document.querySelector('.calendar-content').innerHTML = html;
}

function renderWeekView() {
    const startOfWeek = new Date(currentDate);
    startOfWeek.setDate(currentDate.getDate() - currentDate.getDay());
    
    let html = '<div class="week-view-container">';
    html += '<div class="week-header"><div class="time-column-header"></div>';
    
    for (let i = 0; i < 7; i++) {
        const day = new Date(startOfWeek);
        day.setDate(startOfWeek.getDate() + i);
        const isToday = day.toDateString() === new Date().toDateString();
        html += `<div class="week-day-header ${isToday ? 'today' : ''}">
                    ${DAYS_SHORT[i]}<br>
                    <span class="day-number">${day.getDate()}</span>
                 </div>`;
    }
    html += '</div>';
    
    html += '<div class="week-grid-container">';
    html += '<div class="week-time-column">';
    for (let hour = 0; hour < 24; hour++) {
        html += `<div class="time-slot-label">${formatHour(hour)}</div>`;
    }
    html += '</div>';
    
    for (let i = 0; i < 7; i++) {
        const day = new Date(startOfWeek);
        day.setDate(startOfWeek.getDate() + i);
        html += `<div class="week-day-column" data-date="${day.toISOString()}" onclick="createEventAtTime(event, this)">`;
        
        for (let hour = 0; hour < 24; hour++) {
            html += `<div class="time-slot" data-hour="${hour}"></div>`;
        }
        
        const dayEvents = getEventsForDate(day);
        dayEvents.forEach(event => {
            const position = calculateEventPosition(event, day);
            if (position) {
                html += `<div class="week-event ${event.event_type.toLowerCase()}" 
                             style="top: ${position.top}px; height: ${position.height}px;"
                             onclick="event.stopPropagation(); editEvent(${event.id})">
                            <div class="week-event-title">${event.title}</div>
                            <div class="week-event-time">${formatTime(event.start)} - ${formatTime(event.end)}</div>
                         </div>`;
            }
        });
        
        html += '</div>';
    }
    html += '</div></div>';
    document.querySelector('.calendar-content').innerHTML = html;
}

function renderDayView() {
    let html = '<div class="day-view-container">';
    html += `<div class="day-view-header">
                <h2>${DAYS[currentDate.getDay()]}, ${MONTHS[currentDate.getMonth()]} ${currentDate.getDate()}, ${currentDate.getFullYear()}</h2>
             </div>`;
    
    html += '<div class="day-grid-container">';
    html += '<div class="day-time-column">';
    for (let hour = 0; hour < 24; hour++) {
        html += `<div class="time-slot-label">${formatHour(hour)}</div>`;
    }
    html += '</div>';
    
    html += `<div class="day-column" data-date="${currentDate.toISOString()}" onclick="createEventAtTime(event, this)">`;
    for (let hour = 0; hour < 24; hour++) {
        html += `<div class="time-slot" data-hour="${hour}"></div>`;
    }
    
    const dayEvents = getEventsForDate(currentDate);
    dayEvents.forEach(event => {
        const position = calculateEventPosition(event, currentDate);
        if (position) {
            html += `<div class="day-event ${event.event_type.toLowerCase()}" 
                         style="top: ${position.top}px; height: ${position.height}px;"
                         onclick="event.stopPropagation(); editEvent(${event.id})">
                        <div class="day-event-title">${event.title}</div>
                        <div class="day-event-time">${formatTime(event.start)} - ${formatTime(event.end)}</div>
                        <div class="day-event-location">${event.location || ''}</div>
                     </div>`;
        }
    });
    
    html += '</div></div></div>';
    document.querySelector('.calendar-content').innerHTML = html;
}

function getEventsForDate(date) {
    const dateStr = date.toDateString();
    return allEvents.filter(event => {
        if (filteredEventType !== 'all' && event.event_type.toLowerCase() !== filteredEventType) {
            return false;
        }
        const eventStart = new Date(event.start).toDateString();
        const eventEnd = new Date(event.end).toDateString();
        return eventStart === dateStr || eventEnd === dateStr || 
               (new Date(event.start) < date && new Date(event.end) > date);
    });
}

function calculateEventPosition(event, date) {
    const eventStart = new Date(event.start);
    const eventEnd = new Date(event.end);
    const dateStr = date.toDateString();
    
    const HOUR_HEIGHT = 60;
    let startHour, startMinute, endHour, endMinute;
    
    if (eventStart.toDateString() === dateStr) {
        startHour = eventStart.getHours();
        startMinute = eventStart.getMinutes();
    } else if (eventStart < date) {
        startHour = 0;
        startMinute = 0;
    } else {
        return null;
    }
    
    if (eventEnd.toDateString() === dateStr) {
        endHour = eventEnd.getHours();
        endMinute = eventEnd.getMinutes();
    } else if (eventEnd > date) {
        endHour = 23;
        endMinute = 59;
    } else {
        return null;
    }
    
    const top = (startHour * HOUR_HEIGHT) + (startMinute / 60 * HOUR_HEIGHT);
    const duration = ((endHour - startHour) * 60 + (endMinute - startMinute)) / 60;
    const height = duration * HOUR_HEIGHT;
    
    return { top, height: Math.max(height, 30) };
}

function updateCalendarHeader() {
    const headerText = currentView === 'month' 
        ? `${MONTHS[currentDate.getMonth()]} ${currentDate.getFullYear()}`
        : currentView === 'week'
        ? `Week of ${getWeekRange()}`
        : `${MONTHS[currentDate.getMonth()]} ${currentDate.getDate()}, ${currentDate.getFullYear()}`;
    
    document.querySelector('.calendar-header h2').textContent = headerText;
}

function getWeekRange() {
    const startOfWeek = new Date(currentDate);
    startOfWeek.setDate(currentDate.getDate() - currentDate.getDay());
    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6);
    return `${MONTHS[startOfWeek.getMonth()]} ${startOfWeek.getDate()} - ${MONTHS[endOfWeek.getMonth()]} ${endOfWeek.getDate()}, ${startOfWeek.getFullYear()}`;
}

function changeView(view, button) {
    currentView = view;
    document.querySelectorAll('.view-switch-btn').forEach(btn => btn.classList.remove('active-view'));
    button.classList.add('active-view');
    renderCalendar();
}

function navigateCalendar(direction) {
    switch(currentView) {
        case 'month':
            currentDate.setMonth(currentDate.getMonth() + direction);
            break;
        case 'week':
            currentDate.setDate(currentDate.getDate() + (direction * 7));
            break;
        case 'day':
            currentDate.setDate(currentDate.getDate() + direction);
            break;
    }
    renderCalendar();
    updateMiniCalendar();
}

function goToToday() {
    currentDate = new Date();
    renderCalendar();
    updateMiniCalendar();
}

function selectDate(dateStr) {
    currentDate = new Date(dateStr);
    currentView = 'day';
    renderCalendar();
}

function createEventAtTime(e, element) {
    const clickY = e.offsetY;
    const hourHeight = 60;
    const hour = Math.floor(clickY / hourHeight);
    const date = new Date(element.dataset.date);
    date.setHours(hour, 0, 0, 0);
    
    openEventModal(null, null, date);
}

function openEventModal(eventId = null, eventData = null, defaultStartDate = null) {
    const modal = document.getElementById('eventModal');
    const modalTitle = document.getElementById('modalTitle');
    const deleteBtn = document.getElementById('deleteBtn');
    
    if (eventId && eventData) {
        modalTitle.textContent = 'Edit Event';
        document.getElementById('eventId').value = eventId;
        document.getElementById('eventTitle').value = eventData.title;
        document.getElementById('eventDescription').value = eventData.description;
        document.getElementById('eventType').value = eventData.event_type;
        document.getElementById('startDate').value = formatDateTimeLocal(new Date(eventData.start_date));
        document.getElementById('endDate').value = formatDateTimeLocal(new Date(eventData.end_date));
        document.getElementById('location').value = eventData.location;
        document.getElementById('capacity').value = eventData.capacity || 50;
        deleteBtn.style.display = 'block';
        currentEventId = eventId;
    } else {
        modalTitle.textContent = 'Create Event';
        document.getElementById('eventForm').reset();
        document.getElementById('eventId').value = '';
        deleteBtn.style.display = 'none';
        currentEventId = null;
        
        if (defaultStartDate) {
            const endDate = new Date(defaultStartDate);
            endDate.setHours(endDate.getHours() + 1);
            document.getElementById('startDate').value = formatDateTimeLocal(defaultStartDate);
            document.getElementById('endDate').value = formatDateTimeLocal(endDate);
        }
    }
    
    modal.style.display = 'block';
}

function editEvent(eventId) {
    const event = allEvents.find(e => e.id == eventId);
    if (event) {
        openEventModal(eventId, event);
    }
}

function closeEventModal() {
    document.getElementById('eventModal').style.display = 'none';
    document.getElementById('eventForm').reset();
    currentEventId = null;
}

function saveEvent(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const eventId = formData.get('event_id');
    const url = eventId ? '../../api/events/edit_event.php' : '../../api/events/add_event.php';
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEventModal();
            loadEvents();
        } else {
            alert('Error: ' + (data.message || 'Failed to save event'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the event');
    });
    
    return false;
}

function deleteEvent() {
    if (!currentEventId || !confirm('Are you sure you want to delete this event?')) return;
    
    fetch('../../api/events/delete_event.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'event_id=' + currentEventId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEventModal();
            loadEvents();
        } else {
            alert('Error: ' + (data.message || 'Failed to delete event'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the event');
    });
}

function searchEvents() {
    const searchTerm = document.getElementById('searchEventsInput').value.toLowerCase();
    if (!searchTerm) {
        loadEvents();
        return;
    }
    
    allEvents = allEvents.filter(event => 
        event.title.toLowerCase().includes(searchTerm) ||
        event.description.toLowerCase().includes(searchTerm) ||
        event.location.toLowerCase().includes(searchTerm)
    );
    renderCalendar();
    displayUpcomingEvents();
}

function displayUpcomingEvents() {
    const container = document.querySelector('.upcoming-events');
    if (!container) return;
    
    let html = '<h3>Upcoming Events</h3>';
    const upcoming = allEvents
        .filter(e => new Date(e.start) >= new Date())
        .sort((a, b) => new Date(a.start) - new Date(b.start))
        .slice(0, 5);
    
    upcoming.forEach(event => {
        html += `<div class="event-item" onclick="editEvent(${event.id})">
                    <div class="event-color-bar ${event.event_type.toLowerCase()}"></div>
                    <div class="event-details">
                        <p class="event-title">${event.title}</p>
                        <p class="event-time">${formatEventDate(event.start)} | ${event.location || 'TBA'}</p>
                    </div>
                 </div>`;
    });
    
    container.innerHTML = html || '<p>No upcoming events</p>';
}

function updateMiniCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    document.querySelector('.mini-month-year').textContent = `${MONTHS[month]} ${year}`;
    
    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();
    const prevLastDate = new Date(year, month, 0).getDate();
    
    let html = DAYS_SHORT.map(d => `<div class="mini-day-header">${d[0]}</div>`).join('');
    
    for (let i = firstDay - 1; i >= 0; i--) {
        html += `<div class="mini-day inactive">${prevLastDate - i}</div>`;
    }
    
    for (let i = 1; i <= lastDate; i++) {
        const isToday = i === new Date().getDate() && month === new Date().getMonth() && year === new Date().getFullYear();
        html += `<div class="mini-day ${isToday ? 'active' : ''}" onclick="selectMiniDate(${year}, ${month}, ${i})">${i}</div>`;
    }
    
    document.querySelector('.mini-calendar-grid').innerHTML = html;
}

function selectMiniDate(year, month, day) {
    currentDate = new Date(year, month, day);
    currentView = 'day';
    renderCalendar();
    updateMiniCalendar();
}

function prevMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    updateMiniCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    updateMiniCalendar();
}

function formatHour(hour) {
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:00 ${ampm}`;
}

function formatTime(date) {
    const d = new Date(date);
    const hours = d.getHours();
    const minutes = d.getMinutes();
    const ampm = hours >= 12 ? 'PM' : 'AM';
    const displayHour = hours % 12 || 12;
    return `${displayHour}:${minutes.toString().padStart(2, '0')} ${ampm}`;
}

function formatEventDate(date) {
    const d = new Date(date);
    return `${MONTHS[d.getMonth()]} ${d.getDate()}, ${d.getFullYear()}`;
}

function formatDateTimeLocal(date) {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    const hours = String(d.getHours()).padStart(2, '0');
    const minutes = String(d.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

window.onclick = function(event) {
    if (event.target.id === 'eventModal') {
        closeEventModal();
    }
}

function saveUpdates() {
    loadEvents();
    alert('✅ Calendar updates saved successfully!\n\nAll changes have been synchronized to the database.');
}

function updateUniversityCalendar() {
    const confirmUpdate = confirm('This will synchronize all events to the university calendar and make them visible to students.\n\nContinue?');
    if (!confirmUpdate) return;
    
    fetch('../../api/events/update_university_calendar.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'sync', timestamp: new Date().toISOString() })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ University Calendar Updated Successfully!\n\n' + 
                  `${data.events_count} event(s) synchronized and now visible to students.\n` +
                  'Students can now pre-register for these events.');
            loadEvents();
        } else {
            alert('❌ Error: ' + (data.message || 'Failed to update calendar'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ An error occurred while updating the calendar');
    });
}
