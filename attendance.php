<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Verification - National University</title>
    <link rel="stylesheet" href="student.css">
    <link rel="stylesheet" href="calendar_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .attendance-container {
            max-width: 1200px;
            margin: 100px auto 40px;
            padding: 30px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .page-header {
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 20px;
        }
        
        .page-header h1 {
            color: #333;
            font-size: 28px;
            margin: 0 0 10px 0;
        }
        
        .page-header p {
            color: #666;
            margin: 0;
        }
        
        .verification-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .verification-card {
            background: #f9f9f9;
            padding: 25px;
            border-radius: 12px;
            border: 2px solid #e0e0e0;
        }
        
        .verification-card h2 {
            color: #4a5bb8;
            font-size: 20px;
            margin: 0 0 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .manual-input {
            margin-bottom: 20px;
        }
        
        .manual-input label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        
        .manual-input input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            font-family: monospace;
        }
        
        .manual-input input:focus {
            outline: none;
            border-color: #4a5bb8;
        }
        
        .btn-verify {
            width: 100%;
            padding: 14px;
            background: #4a5bb8;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-verify:hover {
            background: #3a4ba0;
            transform: translateY(-2px);
        }
        
        .qr-scanner-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
        }
        
        #qr-reader {
            width: 100%;
        }
        
        .scanner-status {
            text-align: center;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 8px;
            margin-top: 15px;
            font-size: 14px;
            color: #666;
        }
        
        .result-section {
            margin-top: 30px;
        }
        
        .result-card {
            background: #fff;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            display: none;
        }
        
        .result-card.success {
            border-color: #4caf50;
            background: #f1f8f4;
        }
        
        .result-card.error {
            border-color: #f44336;
            background: #fef1f1;
        }
        
        .result-card.warning {
            border-color: #ff9800;
            background: #fff8f1;
        }
        
        .result-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .result-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .result-card.success .result-icon {
            background: #4caf50;
            color: white;
        }
        
        .result-card.error .result-icon {
            background: #f44336;
            color: white;
        }
        
        .result-card.warning .result-icon {
            background: #ff9800;
            color: white;
        }
        
        .result-title {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
        }
        
        .result-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
        }
        
        .detail-item {
            padding: 12px;
            background: white;
            border-radius: 6px;
        }
        
        .detail-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }
        
        .btn-confirm-attendance {
            width: 100%;
            padding: 14px;
            background: #4caf50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s;
        }
        
        .btn-confirm-attendance:hover {
            background: #45a049;
        }
        
        .recent-checkins {
            margin-top: 40px;
        }
        
        .recent-checkins h2 {
            color: #333;
            font-size: 22px;
            margin-bottom: 20px;
        }
        
        .checkin-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .checkin-table th {
            background: #f5f5f5;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .checkin-table td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .checkin-table tr:hover {
            background: #f9f9f9;
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-badge.attended {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        @media (max-width: 768px) {
            .verification-section {
                grid-template-columns: 1fr;
            }
            
            .result-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <div class="logo-section">
                <div class="logo-icon">
                    <img src="assets/national-university-philippines-logo-png_seeklogo-499282-removebg-preview.png" alt="National University Logo" class="logo-img">
                </div>
                <span class="logo-text">National University</span>
            </div>
            <nav class="nav-links">
                <a href="admin_page.php" class="nav-link">HOME</a>
                <span class="nav-divider">|</span>
                <a href="admin_calendar.php" class="nav-link">CALENDAR</a>
                <span class="nav-divider">|</span>
                <a href="view_events.php" class="nav-link">VIEW EVENTS</a>
                <span class="nav-divider">|</span>
                <a href="attendance.php" class="nav-link active">ATTENDANCE</a>
            </nav>
            <div class="header-icons">
                <button onclick="showProfileMenu()" class="icon-btn user-icon">
                    <i class="fas fa-user-circle"></i>
                </button>
                <button class="icon-btn hamburger-menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <div class="attendance-container">
        <div class="page-header">
            <h1><i class="fas fa-clipboard-check"></i> Event Attendance Verification</h1>
            <p>Scan QR codes or manually enter ticket numbers to verify student attendance</p>
        </div>

        <div class="verification-section">
            <div class="verification-card">
                <h2><i class="fas fa-qrcode"></i> QR Code Scanner</h2>
                <div class="qr-scanner-container">
                    <div id="qr-reader"></div>
                </div>
                <div class="scanner-status" id="scanner-status">
                    <i class="fas fa-spinner fa-spin"></i> Initializing camera...
                </div>
            </div>

            <div class="verification-card">
                <h2><i class="fas fa-keyboard"></i> Manual Entry</h2>
                <form id="manualVerifyForm" onsubmit="verifyTicketManual(event)">
                    <div class="manual-input">
                        <label for="ticketNumber">Ticket Number:</label>
                        <input type="text" id="ticketNumber" name="ticket_number" 
                               placeholder="NU-XXXXXX" 
                               pattern="NU-[0-9]{6}"
                               required>
                        <small style="color: #666; margin-top: 5px; display: block;">
                            Format: NU-XXXXXX (e.g., NU-764787)
                        </small>
                    </div>
                    <button type="submit" class="btn-verify">
                        <i class="fas fa-check"></i> Verify Ticket
                    </button>
                </form>
            </div>
        </div>

        <div class="result-section">
            <div class="result-card" id="resultCard">
                <div class="result-header">
                    <div class="result-icon" id="resultIcon"></div>
                    <div>
                        <h3 class="result-title" id="resultTitle"></h3>
                        <p id="resultMessage" style="margin: 5px 0 0 0; color: #666;"></p>
                    </div>
                </div>
                <div class="result-details" id="resultDetails"></div>
                <button class="btn-confirm-attendance" id="confirmBtn" onclick="confirmAttendance()" style="display: none;">
                    <i class="fas fa-check-circle"></i> Mark as Attended
                </button>
            </div>
        </div>

        <div class="recent-checkins">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0;"><i class="fas fa-history"></i> Recent Check-ins</h2>
                <button onclick="clearRecentCheckins()" 
                        style="padding: 8px 16px; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; transition: all 0.3s;"
                        onmouseover="this.style.background='#c82333'" 
                        onmouseout="this.style.background='#dc3545'">
                    <i class="fas fa-trash-alt"></i> Clear History
                </button>
            </div>
            <table class="checkin-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Ticket Number</th>
                        <th>Student Name</th>
                        <th>Event</th>
                        <th>Section</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="recentCheckinsBody">
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999;">No recent check-ins</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <?php include 'profile_menu_popup.php'; ?>
    
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        let currentTicketData = null;
        let recentCheckins = [];
        let html5QrCode = null;
        
        // Load recent check-ins from localStorage on page load
        function loadRecentCheckins() {
            const saved = localStorage.getItem('recentCheckins');
            if (saved) {
                try {
                    recentCheckins = JSON.parse(saved);
                    updateRecentCheckinsTable();
                } catch (e) {
                    console.error('Error loading check-ins:', e);
                    recentCheckins = [];
                }
            }
        }
        
        // Save recent check-ins to localStorage
        function saveRecentCheckins() {
            try {
                localStorage.setItem('recentCheckins', JSON.stringify(recentCheckins));
            } catch (e) {
                console.error('Error saving check-ins:', e);
            }
        }
        
        // Load check-ins when page loads
        loadRecentCheckins();
        
        // Initialize QR Code Scanner
        function initQRScanner() {
            try {
                html5QrCode = new Html5Qrcode("qr-reader");
                const config = { 
                    fps: 10, 
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                };
                
                html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    onScanSuccess,
                    onScanError
                ).then(() => {
                    document.getElementById('scanner-status').innerHTML = '<i class="fas fa-camera"></i> Camera ready - Point at QR code';
                }).catch(err => {
                    console.error('QR Scanner initialization error:', err);
                    document.getElementById('scanner-status').innerHTML = '<i class="fas fa-exclamation-triangle"></i> Camera unavailable - Use manual entry';
                });
            } catch (err) {
                console.error('QR Scanner error:', err);
                document.getElementById('scanner-status').innerHTML = '<i class="fas fa-exclamation-triangle"></i> Scanner not available - Use manual entry';
            }
        }
        
        function onScanSuccess(decodedText, decodedResult) {
            // Stop scanning temporarily
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.pause(true);
            }
            document.getElementById('scanner-status').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing scan...';
            verifyTicket(decodedText);
        }
        
        function onScanError(errorMessage) {
            // Ignore scanning errors (they happen constantly while scanning)
        }
        
        // Initialize scanner when page loads
        window.addEventListener('load', function() {
            setTimeout(initQRScanner, 500);
        });
        
        function verifyTicketManual(event) {
            event.preventDefault();
            const ticketNumber = document.getElementById('ticketNumber').value.trim();
            verifyTicket(ticketNumber);
            // Clear input immediately for next entry
            document.getElementById('ticketNumber').value = '';
            return false;
        }
        
        function verifyTicket(ticketNumber) {
            fetch('verify_ticket.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'ticket_number=' + encodeURIComponent(ticketNumber)
            })
            .then(response => response.json())
            .then(data => {
                displayResult(data);
                // Resume scanning immediately for next ticket
                if (html5QrCode && html5QrCode.getState() === 2) {
                    html5QrCode.resume();
                    document.getElementById('scanner-status').innerHTML = '<i class="fas fa-camera"></i> Ready for next scan';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Verification failed. Please try again.');
                // Resume scanning immediately after error
                if (html5QrCode && html5QrCode.getState() === 2) {
                    html5QrCode.resume();
                    document.getElementById('scanner-status').innerHTML = '<i class="fas fa-camera"></i> Ready for next scan';
                }
            });
        }
        
        function displayResult(data) {
            const resultCard = document.getElementById('resultCard');
            const resultIcon = document.getElementById('resultIcon');
            const resultTitle = document.getElementById('resultTitle');
            const resultMessage = document.getElementById('resultMessage');
            const resultDetails = document.getElementById('resultDetails');
            const confirmBtn = document.getElementById('confirmBtn');
            
            resultCard.className = 'result-card';
            resultCard.style.display = 'block';
            
            if (data.success) {
                if (data.already_attended) {
                    resultCard.classList.add('warning');
                    resultIcon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
                    resultTitle.textContent = 'Already Attended';
                    resultMessage.textContent = 'This student has already checked in for this event.';
                    confirmBtn.style.display = 'none';
                } else {
                    resultCard.classList.add('success');
                    resultIcon.innerHTML = '<i class="fas fa-check"></i>';
                    resultTitle.textContent = 'Valid Ticket';
                    resultMessage.textContent = 'Student verified successfully. Click below to mark attendance.';
                    confirmBtn.style.display = 'block';
                    currentTicketData = data.registration;
                }
                
                resultDetails.innerHTML = `
                    <div class="detail-item">
                        <div class="detail-label">Student Name</div>
                        <div class="detail-value">${data.registration.student_name}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Ticket Number</div>
                        <div class="detail-value">${data.registration.ticket_number}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Section</div>
                        <div class="detail-value">${data.registration.section}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Course</div>
                        <div class="detail-value">${data.registration.course}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Event</div>
                        <div class="detail-value">${data.registration.event_title}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Event Type</div>
                        <div class="detail-value">${data.registration.event_type}</div>
                    </div>
                `;
            } else {
                resultCard.classList.add('error');
                resultIcon.innerHTML = '<i class="fas fa-times"></i>';
                resultTitle.textContent = 'Invalid Ticket';
                resultMessage.textContent = data.message || 'Ticket not found or invalid.';
                resultDetails.innerHTML = '';
                confirmBtn.style.display = 'none';
            }
        }
        
        function confirmAttendance() {
            if (!currentTicketData) return;
            
            const confirmBtn = document.getElementById('confirmBtn');
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            fetch('mark_attendance.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'ticket_number=' + encodeURIComponent(currentTicketData.ticket_number)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    addRecentCheckin(currentTicketData);
                    showSuccessMessage('Attendance marked successfully!');
                    document.getElementById('resultCard').style.display = 'none';
                    document.getElementById('ticketNumber').value = '';
                    currentTicketData = null;
                } else {
                    alert('Error: ' + data.message);
                }
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="fas fa-check-circle"></i> Mark as Attended';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="fas fa-check-circle"></i> Mark as Attended';
            });
        }
        
        function addRecentCheckin(registration) {
            const now = new Date();
            recentCheckins.unshift({
                time: now.toLocaleTimeString(),
                date: now.toLocaleDateString(),
                ticket_number: registration.ticket_number,
                student_name: registration.student_name,
                event_title: registration.event_title,
                section: registration.section,
                timestamp: now.getTime()
            });
            
            // Keep only last 50 check-ins
            if (recentCheckins.length > 50) {
                recentCheckins = recentCheckins.slice(0, 50);
            }
            
            // Save to localStorage
            saveRecentCheckins();
            
            updateRecentCheckinsTable();
        }
        
        function updateRecentCheckinsTable() {
            const tbody = document.getElementById('recentCheckinsBody');
            if (recentCheckins.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: #999;">No recent check-ins</td></tr>';
                return;
            }
            
            let html = '';
            // Show only last 10 in table
            recentCheckins.slice(0, 10).forEach(checkin => {
                const displayTime = checkin.date ? `${checkin.date} ${checkin.time}` : checkin.time;
                html += `
                    <tr>
                        <td>${displayTime}</td>
                        <td><strong>${checkin.ticket_number}</strong></td>
                        <td>${checkin.student_name}</td>
                        <td>${checkin.event_title}</td>
                        <td>${checkin.section}</td>
                        <td><span class="status-badge attended">Attended</span></td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }
        
        function showSuccessMessage(message) {
            const resultCard = document.getElementById('resultCard');
            resultCard.className = 'result-card success';
            resultCard.style.display = 'block';
            document.getElementById('resultIcon').innerHTML = '<i class="fas fa-check-circle"></i>';
            document.getElementById('resultTitle').textContent = 'Success!';
            document.getElementById('resultMessage').textContent = message;
            document.getElementById('resultDetails').innerHTML = '';
            document.getElementById('confirmBtn').style.display = 'none';
            
            setTimeout(() => {
                resultCard.style.display = 'none';
            }, 3000);
        }
        
        function showError(message) {
            const resultCard = document.getElementById('resultCard');
            resultCard.className = 'result-card error';
            resultCard.style.display = 'block';
            document.getElementById('resultIcon').innerHTML = '<i class="fas fa-times"></i>';
            document.getElementById('resultTitle').textContent = 'Error';
            document.getElementById('resultMessage').textContent = message;
            document.getElementById('resultDetails').innerHTML = '';
            document.getElementById('confirmBtn').style.display = 'none';
        }
        
        function clearRecentCheckins() {
            if (confirm('Are you sure you want to clear all recent check-ins history?\n\nThis will only clear the display history, not the attendance records in the database.')) {
                recentCheckins = [];
                saveRecentCheckins();
                updateRecentCheckinsTable();
                alert('Check-in history cleared successfully!');
            }
        }
    </script>
</body>
</html>

