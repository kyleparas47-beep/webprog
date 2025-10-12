<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_name = $_SESSION['name'] ?? 'User';
$user_email = $_SESSION['email'] ?? '';
$user_role = $_SESSION['role'] ?? '';
?>

<div id="profileMenu" class="profile-menu-simple" style="display: none;">
    <div class="profile-menu-header">
        <button class="profile-menu-close" onclick="closeProfileMenu()">
            <i class="fas fa-times"></i>
        </button>
        <h3>Profile Information</h3>
    </div>
    
    <div class="profile-menu-user">
        <div class="profile-avatar">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user_name) ?>&background=1976d2&color=fff&size=80" alt="Profile Picture">
        </div>
        <div class="profile-details">
            <div class="profile-name"><?= htmlspecialchars($user_name) ?></div>
            <div class="profile-email"><?= htmlspecialchars($user_email) ?></div>
        </div>
    </div>
    
    <div class="profile-menu-options">
        <a href="MyProfilePage.php" class="profile-menu-item">
            <i class="fas fa-user"></i>
            <span>My Profile</span>
            <i class="fas fa-chevron-right"></i>
        </a>
        
        <a href="#" class="profile-menu-item" onclick="showSettings()">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
            <i class="fas fa-chevron-right"></i>
        </a>
        
        <a href="#" class="profile-menu-item" onclick="showNotifications()">
            <i class="fas fa-bell"></i>
            <span>Notifications</span>
            <i class="fas fa-chevron-right"></i>
        </a>
        
        <a href="logout.php" class="profile-menu-item logout-item">
            <i class="fas fa-sign-out-alt"></i>
            <span>Log Out</span>
            <i class="fas fa-chevron-right"></i>
        </a>
    </div>
</div>

<div id="profileMenuOverlay" class="profile-menu-overlay-simple" style="display: none;" onclick="closeProfileMenu()"></div>

<style>
.profile-menu-overlay-simple {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 99999;
}

.profile-menu-simple {
    position: fixed;
    top: 0;
    right: 0;
    width: 350px;
    height: 100vh;
    background: linear-gradient(135deg, #e8eaf6 0%, #f3e5f5 100%);
    border-left: 1px solid #d1c4e9;
    z-index: 100000;
    display: flex;
    flex-direction: column;
    box-shadow: -5px 0 20px rgba(0, 0, 0, 0.1);
}

.profile-menu-header {
    padding: 20px;
    border-bottom: 1px solid #d1c4e9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(255, 255, 255, 0.8);
}

.profile-menu-header h3 {
    margin: 0;
    color: #1a237e;
    font-size: 18px;
    font-weight: 600;
}

.profile-menu-close {
    background: none;
    border: none;
    font-size: 20px;
    color: #666;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: background-color 0.2s;
}

.profile-menu-close:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

.profile-menu-user {
    padding: 25px 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    background: rgba(255, 255, 255, 0.6);
    border-bottom: 1px solid #d1c4e9;
}

.profile-avatar img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 3px solid #1976d2;
    object-fit: cover;
}

.profile-details {
    flex: 1;
}

.profile-name {
    font-size: 20px;
    font-weight: 700;
    color: #1a237e;
    margin-bottom: 5px;
}

.profile-email {
    font-size: 14px;
    color: #666;
    font-weight: 500;
}

.profile-menu-options {
    flex: 1;
    padding: 20px 0;
    overflow-y: auto;
}

.profile-menu-item {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    text-decoration: none;
    color: #37477b;
    font-weight: 600;
    transition: all 0.2s;
    border-bottom: 1px solid rgba(209, 196, 233, 0.3);
}

.profile-menu-item:hover {
    background: rgba(255, 255, 255, 0.8);
    color: #1a237e;
    transform: translateX(5px);
}

.profile-menu-item i:first-child {
    width: 20px;
    margin-right: 15px;
    font-size: 16px;
}

.profile-menu-item span {
    flex: 1;
}

.profile-menu-item i:last-child {
    font-size: 12px;
    opacity: 0.6;
}

.profile-menu-item.logout-item {
    color: #d32f2f;
    border-top: 2px solid rgba(211, 47, 47, 0.2);
    margin-top: 10px;
}

.profile-menu-item.logout-item:hover {
    background: rgba(211, 47, 47, 0.1);
    color: #b71c1c;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .profile-menu-simple {
        width: 100%;
    }
}
</style>

<script>
function showProfileMenu() {
    console.log('showProfileMenu called (simple version)');
    const menu = document.getElementById('profileMenu');
    const overlay = document.getElementById('profileMenuOverlay');
    
    if (menu && overlay) {
        console.log('Showing menu...');
        menu.style.display = 'flex';
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
    } else {
        console.error('Menu elements not found');
    }
}

function closeProfileMenu() {
    console.log('closeProfileMenu called (simple version)');
    const menu = document.getElementById('profileMenu');
    const overlay = document.getElementById('profileMenuOverlay');
    
    if (menu && overlay) {
        menu.style.display = 'none';
        overlay.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

function showSettings() {
    closeProfileMenu();
    alert('Settings functionality coming soon!');
}

function showNotifications() {
    closeProfileMenu();
    alert('Notifications functionality coming soon!');
}

// Close menu when pressing Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProfileMenu();
    }
});
</script>
