<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - National University</title>
    <link rel="stylesheet" href="/student.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #4a4e9e;
            color: #31344d;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-container {
            background-color: #d8dce7;
            border-radius: 12px;
            width: 400px;
            padding: 2rem;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.15);
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            color: #454995;
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
        }

        .back-btn {
            background: transparent;
            border: none;
            font-size: 1.4rem;
            color: #454995;
            cursor: pointer;
            user-select: none;
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.8rem;
        }

        .avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 2px solid #37477b;
            object-fit: cover;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #454995;
        }

        .email {
            font-size: 1rem;
            color: #7a7c90;
            font-weight: 600;
            margin-top: 0.1rem;
        }

        .update-item {
            height: 60px;
            background-color: white;
            border-radius: 10px;
            padding: 0.6rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 700;
            color: #37477b;
            cursor: pointer;
            box-shadow: 3px 3px 10px #bebfe0, -3px -3px 10px #ffffff;
            transition: background-color 0.3s;
        }

        .update-item:hover {
            background-color: #e5e9fb;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2rem;
        }

        .action-button {
            height: 50px;
            background-color: white;
            border-radius: 10px;
            border: none;
            font-weight: 700;
            color: #37477b;
            cursor: pointer;
            box-shadow: 3px 3px 10px #bebfe0, -3px -3px 10px #ffffff;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-button:hover {
            background-color: #d3d6ed;
        }
    </style>
</head>
<body onload="initPage()">
    <div class="profile-container">
        <header class="profile-header">
            <button class="back-btn" onclick="goBack()">&larr;</button>
            <h2>Profile Information</h2>
        </header>
        <div class="profile-info">
            <img class="avatar" src="https://randomuser.me/api/portraits/men/76.jpg" alt="Avatar of Dela Cruz, Juan" />
            <div class="user-details">
                <div class="name">Dela Cruz, Juan</div>
                <div class="email">1delacruz@gmail.com</div>
            </div>
        </div>
        <div class="update-item" onclick="updateName()">
            <span>Update Name:</span>
            <span>&gt;</span>
        </div>
        <div class="update-item" onclick="updateEmail()">
            <span>Update Email:</span>
            <span>&gt;</span>
        </div>
        <div class="action-buttons">
            <button class="action-button" onclick="saveChanges()">save changes</button>
            <button class="action-button" onclick="goBack()">Go back</button>
        </div>
    </div>

    <script>
        function initPage() {
            document.querySelector('.profile-container').style.opacity = 1;
        }

        function goBack() {
            // Add smooth transition out effect
            document.querySelector('.profile-container').style.opacity = 0;
            setTimeout(() => {
                window.history.back();
            }, 500); // Match the transition duration
        }

        function updateName() {
            // Logic to update name
            alert('Update Name clicked');
        }

        function updateEmail() {
            // Logic to update email
            alert('Update Email clicked');
        }

        function saveChanges() {
            // Logic to save changes
            alert('Changes saved');
        }
    </script>
</body>
</html>
