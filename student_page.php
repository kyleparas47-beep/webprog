<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student | Home - National University</title>
    <link rel="stylesheet" href="student.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                <a href="#" class="nav-link active">HOME</a>
                <span class="nav-divider">|</span>
                <a onclick="window.location.href='calendar.php'" class="nav-link">CALENDAR</a>
                <span class="nav-divider">|</span>
                <a href="#" class="nav-link">VIEW EVENTS</a>
            </nav>

            <div class="header-icons">
                <button onclick="window.location.href='logout.html'" class="icon-btn user-icon">
                    <i class="fas fa-user-circle"></i>
                </button>
                <button class="icon-btn hamburger-menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="content-container">
            <nav class="breadcrumb">
                <span class="breadcrumb-item">HOME</span>
                <span class="breadcrumb-separator">></span>
                <span class="breadcrumb-item">EVENTS</span>
            </nav>

            <div class="content-layout">
                <div class="content-left">
                    <h1 class="main-heading">EVENT CALENDAR</h1>
                    <h2 class="sub-heading">National University - Laguna</h2>
                    <p class="school-year">school year 2024–2025</p>
                    
                    <div class="action-section">
                        <a href="#" class="events-link">
                            <span class="link-text">see current events</span>
                            <i class="fas fa-calendar-alt calendar-icon"></i>
                        </a>
                    </div>
                </div>
                <div class="content-right">
                    <div class="image-container">
                        <div class="image-card image-front">
                            <img src="assets\492508047_1000140838968339_1613408679840476886_n.jpg" alt="National University students celebrating and cheering in blue uniforms" class="event-image">
                        </div>
                        <div class="image-card image-back">
                            <img src="assets\images.jpg" alt="National University campus event with students gathered outdoors" class="event-image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>