<?php
// Profile Menu Popup
// This file is included in pages to display the user profile menu
if (!isset($_SESSION['user_id'])) {
    return;
}

$user_role = $_SESSION['role'] ?? 'student';
$user_name = $_SESSION['name'] ?? 'User';
?>

<div id="profilePopup" class="profile-popup" style="display: none;">
    <div class="profile-popup-header">
        <div class="profile-popup-avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="profile-popup-info">
            <div class="profile-popup-name"><?= htmlspecialchars($user_name) ?></div>
            <div class="profile-popup-role"><?= ucfirst($user_role) ?></div>
        </div>
    </div>
    
    <div class="profile-popup-options">
        <a href="<?= $user_role === 'student' ? '../../pages/student/profile.php' : '../../pages/admin/profile.php' ?>" class="profile-popup-item">
            <i class="fas fa-user"></i>
            <span>My Profile</span>
            <i class="fas fa-chevron-right"></i>
        </a>
        
        <a href="../../api/auth/logout.php" class="profile-popup-item logout-item">
            <i class="fas fa-sign-out-alt"></i>
            <span>Log Out</span>
            <i class="fas fa-chevron-right"></i>
        </a>
    </div>
</div>

<style>
.profile-popup {
    position: absolute;
    top: 60px;
    right: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    min-width: 250px;
    z-index: 1000;
    overflow: hidden;
}

.profile-popup-header {
    background: #2f3879;
    padding: 20px;
    color: white;
    display: flex;
    align-items: center;
    gap: 15px;
}

.profile-popup-avatar i {
    font-size: 48px;
}

.profile-popup-name {
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 4px;
}

.profile-popup-role {
    font-size: 13px;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.profile-popup-options {
    padding: 8px 0;
}

.profile-popup-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: #333;
    text-decoration: none;
    transition: background 0.2s;
}

.profile-popup-item:hover {
    background: #f5f5f5;
}

.profile-popup-item i:first-child {
    width: 20px;
    text-align: center;
    color: #2f3879;
}

.profile-popup-item span {
    flex: 1;
    font-size: 14px;
}

.profile-popup-item i.fa-chevron-right {
    font-size: 12px;
    color: #999;
}

.profile-popup-item.logout-item i:first-child {
    color: #e74c3c;
}

.profile-popup-item.logout-item:hover {
    background: #fee;
}
</style>

<script>
function showProfileMenu() {
    const popup = document.getElementById('profilePopup');
    if (popup.style.display === 'none' || popup.style.display === '') {
        popup.style.display = 'block';
    } else {
        popup.style.display = 'none';
    }
}

// Close popup when clicking outside
document.addEventListener('click', function(event) {
    const popup = document.getElementById('profilePopup');
    const userIcon = document.querySelector('.user-icon');
    
    if (popup && userIcon && !popup.contains(event.target) && !userIcon.contains(event.target)) {
        popup.style.display = 'none';
    }
});
</script>
