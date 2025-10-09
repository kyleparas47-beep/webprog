let currentEventId = null;

function openEventModal(eventId = null, eventData = null) {
    const modal = document.getElementById('eventModal');
    const modalTitle = document.getElementById('modalTitle');
    const deleteBtn = document.getElementById('deleteBtn');
    
    if (eventId && eventData) {
        modalTitle.textContent = 'Edit Event';
        document.getElementById('eventId').value = eventId;
        document.getElementById('eventTitle').value = eventData.title;
        document.getElementById('eventDescription').value = eventData.description;
        document.getElementById('eventType').value = eventData.event_type;
        document.getElementById('startDate').value = eventData.start_date;
        document.getElementById('endDate').value = eventData.end_date;
        document.getElementById('location').value = eventData.location;
        deleteBtn.style.display = 'block';
        currentEventId = eventId;
    } else {
        modalTitle.textContent = 'Create Event';
        document.getElementById('eventForm').reset();
        document.getElementById('eventId').value = '';
        deleteBtn.style.display = 'none';
        currentEventId = null;
    }
    
    modal.style.display = 'block';
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
    const url = eventId ? 'edit_event.php' : 'add_event.php';
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(eventId ? 'Event updated successfully!' : 'Event created successfully!');
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
    if (!currentEventId) return;
    
    if (!confirm('Are you sure you want to delete this event?')) {
        return;
    }
    
    fetch('delete_event.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'event_id=' + currentEventId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Event deleted successfully!');
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

function loadEvents() {
    fetch('get_events.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayEvents(data.events);
            }
        })
        .catch(error => {
            console.error('Error loading events:', error);
        });
}

function displayEvents(events) {
    const upcomingEventsContainer = document.querySelector('.upcoming-events');
    if (!upcomingEventsContainer) return;
    
    let eventsHTML = '<h3>Upcoming Events</h3>';
    
    events.forEach(event => {
        const startDate = new Date(event.start_date);
        const endDate = new Date(event.end_date);
        const dateRange = formatDateRange(startDate, endDate);
        
        eventsHTML += `
            <div class="event-item" onclick="openEventModal(${event.id}, ${JSON.stringify(event).replace(/"/g, '&quot;')})">
                <div class="event-color-bar ${event.event_type.toLowerCase()}"></div>
                <div class="event-details">
                    <p class="event-title">${event.title}</p>
                    <p class="event-time">${dateRange} | ${event.location || 'TBA'}</p>
                </div>
            </div>
        `;
    });
    
    upcomingEventsContainer.innerHTML = eventsHTML;
}

function formatDateRange(start, end) {
    const options = { month: 'short', day: 'numeric', year: 'numeric' };
    if (start.toDateString() === end.toDateString()) {
        return start.toLocaleDateString('en-US', options);
    } else {
        return `${start.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })} - ${end.toLocaleDateString('en-US', options)}`;
    }
}

function prevMonth() {
    console.log('Previous month');
}

function nextMonth() {
    console.log('Next month');
}

window.onclick = function(event) {
    const modal = document.getElementById('eventModal');
    if (event.target == modal) {
        closeEventModal();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    loadEvents();
});
