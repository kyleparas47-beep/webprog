<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit();
}
?>
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
                <a href="student_page.php" class="nav-link active">HOME</a>
                <span class="nav-divider">|</span>
                <a href="student_calendar.php" class="nav-link">CALENDAR</a>
                <span class="nav-divider">|</span>
                <a href="view_events.php" class="nav-link">VIEW EVENTS</a>
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

    <div class="marquee-container">
        <div class="marquee-content">
            <span> Events Calendar</span>
            <span> National University</span>
            <span> Events Calendar</span>
            <span> National University</span>
            <span> Events Calendar</span>
            <span> National University</span>
            <span> Events Calendar</span>
            <span> National University</span>
        </div>
    </div>

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
                    <p class="school-year">Academic Year 2024-2025</p>
                    
                    <div class="action-section">
                        <button class="see-events-btn" onclick="window.location.href='student_calendar.php'">
                            <span>See Current Events</span>
                            <i class="fas fa-calendar-alt"></i>
                        </button>
                        
                        <div class="social-media-section">
                            <h3 class="social-title">Follow Us</h3>
                            <div class="social-icons">
                                <a href="#" class="social-icon facebook" data-platform="facebook" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="social-icon instagram" data-platform="instagram" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="social-icon twitter" data-platform="twitter" title="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="social-icon youtube" data-platform="youtube" title="YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <a href="#" class="social-icon linkedin" data-platform="linkedin" title="LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-right">
                    <div class="images-container">
                        <div class="carousel">
                            <div class="carousel-track">
                                <div class="carousel-slide">
                                    <img src="assets/492508047_1000140838968339_1613408679840476886_n.jpg" alt="National University students celebrating and cheering in blue uniforms">
                                </div>
                                <div class="carousel-slide">
                                    <img src="assets/images.jpg" alt="National University campus event with students gathered outdoors">
                                </div>
                                <div class="carousel-slide">
                                    <img src="assets/492508047_1000140838968339_1613408679840476886_n.jpg" alt="Students at university event">
                                </div>
                                <div class="carousel-slide">
                                    <img src="assets/images.jpg" alt="Campus activities">
                                </div>
                                <div class="carousel-slide">
                                    <img src="assets/492508047_1000140838968339_1613408679840476886_n.jpg" alt="University celebration">
                                </div>
                            </div>
                            
                            <div class="carousel-nav">
                                <button class="carousel-btn prev-btn" aria-label="Previous image">
                                    <svg viewBox="0 0 24 24" fill="none">
                                        <path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                <button class="carousel-btn next-btn" aria-label="Next image">
                                    <svg viewBox="0 0 24 24" fill="none">
                                        <path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="carousel-indicators">
                                <span class="indicator active" data-slide="0"></span>
                                <span class="indicator" data-slide="1"></span>
                                <span class="indicator" data-slide="2"></span>
                                <span class="indicator" data-slide="3"></span>
                                <span class="indicator" data-slide="4"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'profile_menu_popup.php'; ?>
    <script src="script.js"></script>
</body>
</html>
