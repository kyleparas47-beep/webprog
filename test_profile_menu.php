<?php
session_start();
// For testing purposes, set some dummy session data
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['name'] = 'Test User';
    $_SESSION['email'] = 'test@example.com';
    $_SESSION['role'] = 'admin';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Profile Menu</title>
    <link rel="stylesheet" href="fonts.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: "Poppins", sans-serif;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .test-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .test-button {
            background: #1976d2;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px;
        }
        .test-button:hover {
            background: #1565c0;
        }
        .debug-info {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>Profile Menu Test Page</h1>
        
        <div class="debug-info">
            <h3>Debug Information:</h3>
            <p><strong>User ID:</strong> <?= $_SESSION['user_id'] ?? 'Not set' ?></p>
            <p><strong>Name:</strong> <?= $_SESSION['name'] ?? 'Not set' ?></p>
            <p><strong>Email:</strong> <?= $_SESSION['email'] ?? 'Not set' ?></p>
            <p><strong>Role:</strong> <?= $_SESSION['role'] ?? 'Not set' ?></p>
        </div>
        
        <h3>Test Buttons:</h3>
        <button class="test-button" onclick="showProfileMenu()">
            <i class="fas fa-user-circle"></i> Show Profile Menu
        </button>
        
        <button class="test-button" onclick="testFunction()">
            <i class="fas fa-bug"></i> Test Function Call
        </button>
        
        <button class="test-button" onclick="checkElements()">
            <i class="fas fa-search"></i> Check Elements
        </button>
        
        <div id="test-results" class="debug-info" style="margin-top: 20px;">
            <h3>Test Results:</h3>
            <div id="results-content">Click buttons above to test...</div>
        </div>
    </div>

    <?php include 'profile_menu.php'; ?>
    
    <script>
        function testFunction() {
            document.getElementById('results-content').innerHTML = 
                '<p style="color: green;">✅ Function call works! showProfileMenu is available: ' + 
                (typeof showProfileMenu === 'function' ? 'YES' : 'NO') + '</p>';
        }
        
        function checkElements() {
            const menu = document.getElementById('profileMenu');
            const overlay = document.getElementById('profileMenuOverlay');
            
            let results = '<h4>Element Check Results:</h4>';
            results += '<p><strong>profileMenu element:</strong> ' + (menu ? '✅ Found' : '❌ Not found') + '</p>';
            results += '<p><strong>profileMenuOverlay element:</strong> ' + (overlay ? '✅ Found' : '❌ Not found') + '</p>';
            
            if (menu) {
                results += '<p><strong>Menu classes:</strong> ' + menu.className + '</p>';
                results += '<p><strong>Menu style display:</strong> ' + window.getComputedStyle(menu).display + '</p>';
            }
            
            document.getElementById('results-content').innerHTML = results;
        }
        
        // Override console.log to show in page
        const originalLog = console.log;
        console.log = function(...args) {
            originalLog.apply(console, args);
            const resultsDiv = document.getElementById('results-content');
            if (resultsDiv) {
                resultsDiv.innerHTML += '<p style="color: blue;">Console: ' + args.join(' ') + '</p>';
            }
        };
        
        // Override console.error to show in page
        const originalError = console.error;
        console.error = function(...args) {
            originalError.apply(console, args);
            const resultsDiv = document.getElementById('results-content');
            if (resultsDiv) {
                resultsDiv.innerHTML += '<p style="color: red;">Error: ' + args.join(' ') + '</p>';
            }
        };
    </script>
</body>
</html>
