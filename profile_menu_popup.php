<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_name = $_SESSION['name'] ?? 'User';
$user_email = $_SESSION['email'] ?? '';
$user_role = $_SESSION['role'] ?? '';
?>

<div id="profileMenu" class="profile-popup" style="display: none;">
    <div class="profile-popup-content">
        <div class="profile-popup-header">
            <button class="profile-popup-close" onclick="closeProfileMenu()">
                <i class="fas fa-times"></i>
            </button>
            <h3>Profile Information</h3>
        </div>
        
        <div class="profile-popup-user">
            <div class="profile-popup-avatar">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user_name) ?>&background=1976d2&color=fff&size=100" alt="Profile Picture">
            </div>
            <div class="profile-popup-details">
                <div class="profile-popup-name"><?= htmlspecialchars($user_name) ?></div>
                <div class="profile-popup-email"><?= htmlspecialchars($user_email) ?></div>
            </div>
        </div>
        
        <div class="profile-popup-options">
            <a href="MyProfilePage.php" class="profile-popup-item">
                <i class="fas fa-user"></i>
                <span>My Profile</span>
                <i class="fas fa-chevron-right"></i>
            </a>
            
            <a href="logout.php" class="profile-popup-item logout-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Log Out</span>
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

<div id="profileMenuOverlay" class="profile-popup-overlay" style="display: none;" onclick="closeProfileMenu()"></div>

<style>
*:not(.fa):not(.fas):not(.far):not(.fab):not(.fal):not(.fad):not([class*="fa-"]) {
    font-family: "Poppins", sans-serif !important;
}

.profile-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(44, 48, 80, 0.8);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(5px);
    opacity: 0;
    transition: opacity 0.2s ease-out;
}

.profile-popup-overlay.show {
    opacity: 1;
}

.profile-popup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 100000;
    width: 90%;
    max-width: 400px;
    max-height: 80vh;
    overflow-y: auto;
}

.profile-popup-content {
    background: linear-gradient(135deg, #dde0f0 0%, #e8eaf6 100%);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.2);
    overflow: hidden;
    animation: zoomIn 0.2s ease-out;
}

@keyframes zoomIn {
    from {
        opacity: 0;
        transform: translate(-50%, -50%) scale(0.8);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }
}

.profile-popup-header {
    background: linear-gradient(135deg, #2c3050 0%, #37477b 100%);
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: white;
}

.profile-popup-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: white;
}

.profile-popup-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: background-color 0.2s;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-popup-close:hover {
    background: rgba(255, 255, 255, 0.3);
}

.profile-popup-user {
    padding: 30px 20px;
    text-align: center;
    background: rgba(255, 255, 255, 0.6);
    border-bottom: 1px solid rgba(209, 196, 233, 0.3);
}

.profile-popup-avatar img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 4px solid #1976d2;
    object-fit: cover;
    margin-bottom: 15px;
    box-shadow: 0 8px 20px rgba(25, 118, 210, 0.3);
}

.profile-popup-details {
    text-align: center;
}

.profile-popup-name {
    font-size: 24px;
    font-weight: 700;
    color: #2c3050;
    margin-bottom: 8px;
}

.profile-popup-email {
    font-size: 16px;
    color: #666;
    font-weight: 500;
}

.profile-popup-options {
    padding: 20px 0;
    background: rgba(255, 255, 255, 0.4);
}

.profile-popup-item {
    display: flex;
    align-items: center;
    padding: 20px 25px;
    text-decoration: none;
    color: #37477b;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-bottom: 1px solid rgba(209, 196, 233, 0.2);
    position: relative;
    background: transparent;
}

.profile-popup-item:hover {
    background: rgba(255, 255, 255, 0.9);
    color: #2c3050;
    transform: translateX(8px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.profile-popup-item i:first-child {
    width: 24px;
    margin-right: 18px;
    font-size: 18px;
    color: #666;
    transition: all 0.3s ease;
}

.profile-popup-item span {
    flex: 1;
    transition: all 0.3s ease;
}

.profile-popup-item i:last-child {
    font-size: 14px;
    opacity: 0.6;
    color: #999;
    transition: all 0.3s ease;
}

.profile-popup-item:hover i:first-child {
    color: #1976d2;
    transform: scale(1.1);
}

.profile-popup-item:hover i:last-child {
    opacity: 1;
    color: #1976d2;
    transform: translateX(3px);
}

.profile-popup-item.logout-item {
    color: #d32f2f;
    border-top: 2px solid rgba(211, 47, 47, 0.2);
    margin-top: 10px;
}

.profile-popup-item.logout-item:hover {
    background: rgba(211, 47, 47, 0.1);
    color: #b71c1c;
}

.profile-popup-item.logout-item i:first-child {
    color: #d32f2f;
}

@media (max-width: 768px) {
    .profile-popup {
        width: 95%;
        max-width: 350px;
    }
    
    .profile-popup-content {
        border-radius: 15px;
    }
    
    .profile-popup-header {
        padding: 15px;
    }
    
    .profile-popup-user {
        padding: 20px 15px;
    }
    
    .profile-popup-avatar img {
        width: 80px;
        height: 80px;
    }
    
    .profile-popup-name {
        font-size: 20px;
    }
    
    .profile-popup-email {
        font-size: 14px;
    }
    
    .profile-popup-item {
        padding: 15px 20px;
        font-size: 15px;
    }
}

.profile-popup.closing .profile-popup-content {
    animation: zoomOut 0.15s ease-in;
}

@keyframes zoomOut {
    from {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }
    to {
        opacity: 0;
        transform: translate(-50%, -50%) scale(0.8);
    }
}
</style>

<script>
function showProfileMenu() {
    console.log('showProfileMenu called (popup version)');
    const popup = document.getElementById('profileMenu');
    const overlay = document.getElementById('profileMenuOverlay');
    
    if (popup && overlay) {
        console.log('Showing popup menu...');
        popup.style.display = 'block';
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        popup.classList.remove('closing');
        setTimeout(() => {
            overlay.classList.add('show');
        }, 10);
    } else {
        console.error('Popup elements not found');
    }
}

function closeProfileMenu() {
    console.log('closeProfileMenu called (popup version)');
    const popup = document.getElementById('profileMenu');
    const overlay = document.getElementById('profileMenuOverlay');
    
    if (popup && overlay) {
        overlay.classList.remove('show');
        popup.classList.add('closing');
        
        setTimeout(function() {
            popup.style.display = 'none';
            overlay.style.display = 'none';
            document.body.style.overflow = 'auto';
            popup.classList.remove('closing');
        }, 150);
    }
}


document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProfileMenu();
    }
});

document.addEventListener('click', function(e) {
    const popup = document.getElementById('profileMenu');
    const overlay = document.getElementById('profileMenuOverlay');
    
    if (e.target === overlay && popup && popup.style.display === 'block') {
        closeProfileMenu();
    }
});
</script>

