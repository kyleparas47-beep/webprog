# Email Verification Setup Guide for Password Reset

## ✅ Email Sending Implemented!

I've updated the password reset system to send actual emails. However, email sending requires proper configuration on your server.

---

## 🔧 Setup Options

### **Option 1: Configure XAMPP to Send Emails (Recommended for Testing)**

Since you're using XAMPP, you need to configure it to send emails:

#### **Step 1: Install a Mail Server**

**Download and Install:** [hMailServer](https://www.hmailserver.com/download) (Free for Windows)

1. Download hMailServer
2. Install it (keep default settings)
3. During installation, use password: `admin` (or your choice)

#### **Step 2: Configure hMailServer**

1. Open **hMailServer Administrator**
2. Connect using password you set
3. **Add Domain:**
   - Right-click "Domains" → Add Domain
   - Name: `nu-laguna.edu.ph`
   - Click "Save"

4. **Add Email Account:**
   - Expand "Domains" → `nu-laguna.edu.ph` → Accounts
   - Right-click → Add
   - Address: `noreply@nu-laguna.edu.ph`
   - Password: choose a password
   - Click "Save"

5. **Configure Settings:**
   - Go to Settings → Protocols → SMTP
   - Delivery of email: Tab
   - SMTP Relayer: `smtp.gmail.com`
   - Port: `587`
   - Check "Server requires authentication"
   - Username: your Gmail
   - Password: your Gmail App Password (see below)

#### **Step 3: Configure PHP (php.ini)**

1. Open `C:\xampp\php\php.ini`
2. Find and update these lines:

```ini
[mail function]
SMTP = localhost
smtp_port = 25
sendmail_from = noreply@nu-laguna.edu.ph
```

3. Save and restart Apache

---

### **Option 2: Use Gmail SMTP (More Reliable)**

This method uses Gmail to send emails. You'll need PHPMailer.

#### **Step 1: Install PHPMailer**

**Method A: Using Composer (Recommended)**
```bash
cd C:\xampp\htdocs\webprogsystem
composer require phpmailer/phpmailer
```

**Method B: Manual Download**
1. Download PHPMailer from: https://github.com/PHPMailer/PHPMailer/releases
2. Extract to: `C:\xampp\htdocs\webprogsystem\PHPMailer`

#### **Step 2: Get Gmail App Password**

1. Go to your Google Account: https://myaccount.google.com/
2. Security → 2-Step Verification (turn ON if not enabled)
3. App passwords → Generate new app password
4. Select "Mail" and "Windows Computer"
5. Copy the 16-character password (e.g., `abcd efgh ijkl mnop`)

#### **Step 3: Create Gmail Email Sender**

Create file: `send_email_gmail.php`

```php
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // If using Composer
// OR
// require 'PHPMailer/src/Exception.php';
// require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/SMTP.php';

function sendResetCodeGmail($email, $code) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@gmail.com';  // ← Change this
        $mail->Password   = 'your-app-password';      // ← Change this (16-char app password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('your-email@gmail.com', 'NU Laguna Events');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Code - NU Laguna';
        $mail->Body    = getEmailTemplate($code);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email Error: {$mail->ErrorInfo}");
        return false;
    }
}

function getEmailTemplate($code) {
    return "<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4a5bb8; color: white; padding: 20px; text-align: center; }
        .code-box { background: #f0f0f0; padding: 20px; text-align: center; margin: 20px 0; }
        .code { font-size: 36px; font-weight: bold; color: #4a5bb8; letter-spacing: 8px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>Password Reset</h1>
        </div>
        <div class='content' style='background:white;padding:20px;'>
            <p>Your verification code is:</p>
            <div class='code-box'>
                <div class='code'>$code</div>
            </div>
            <p>This code expires in 15 minutes.</p>
        </div>
    </div>
</body>
</html>";
}
?>
```

Then update `forgot_password.php` and `resend_code.php` to use this:

```php
require_once 'send_email_gmail.php';

function sendResetCode($email, $code) {
    $_SESSION['reset_code'] = $code;
    return sendResetCodeGmail($email, $code);
}
```

---

### **Option 3: Testing Locally (Development Only)**

For testing purposes, you can check the verification code from:

1. **Check PHP Error Log:**
   - Look in: `C:\xampp\php\logs\php_error_log`
   - The code will be logged there
   - Example: `Password reset code for email@example.com: 123456`

2. **Check Database:**
   ```sql
   SELECT * FROM password_resets ORDER BY id DESC LIMIT 1;
   ```
   - The code is stored in the `token` column

3. **Check Browser Console:**
   - For testing, you can temporarily display the code on screen
   - (Remove this in production!)

---

## 🧪 Testing Email Functionality

### **Test 1: Check if mail() function works**

Create file: `test_email.php`

```php
<?php
$to = "your-email@gmail.com";  // Your email
$subject = "Test Email from XAMPP";
$message = "This is a test email. If you receive this, email is working!";
$headers = "From: noreply@nu-laguna.edu.ph";

if(mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully! Check your inbox.";
} else {
    echo "Email sending failed. Please configure SMTP.";
}
?>
```

Visit: `http://localhost/webprogsystem/test_email.php`

### **Test 2: Check Error Logs**

Check if emails are being attempted:
- Windows: `C:\xampp\php\logs\php_error_log`
- Look for lines like: `Password reset code for...`

### **Test 3: Try Password Reset**

1. Go to forgot password page
2. Enter your email (use your real email for testing)
3. Check:
   - Your inbox
   - Spam folder
   - Error log file
   - Database `password_resets` table

---

## 📧 What Happens Now

### **When User Requests Password Reset:**

1. User enters email on forgot password page
2. System generates 6-digit code (e.g., `123456`)
3. Code is saved to database (`password_resets` table)
4. Code is also saved to session (backup)
5. **EMAIL IS SENT** with the code
6. Code expires after 15 minutes
7. User enters code on verification page

### **Email Template Looks Like:**

```
┌─────────────────────────────────────┐
│   🔒 Password Reset Request         │
│                                     │
│   Your verification code is:        │
│                                     │
│   ┌─────────────────────┐          │
│   │     1 2 3 4 5 6     │          │
│   └─────────────────────┘          │
│                                     │
│   ⏰ Expires in 15 minutes          │
│                                     │
│   National University Laguna        │
└─────────────────────────────────────┘
```

---

## 🐛 Troubleshooting

### **Email Not Received?**

**Check 1: Spam Folder**
- Check your spam/junk folder

**Check 2: Error Log**
```php
// Check: C:\xampp\php\logs\php_error_log
// Look for: "Password reset code for..."
```

**Check 3: Database**
```sql
SELECT * FROM password_resets WHERE user_id = YOUR_USER_ID ORDER BY id DESC LIMIT 1;
```

**Check 4: PHP mail() enabled**
```php
<?php
if (function_exists('mail')) {
    echo "mail() function is available";
} else {
    echo "mail() function is NOT available";
}
?>
```

### **Common Issues:**

1. **XAMPP doesn't send emails by default**
   - Solution: Install hMailServer or use Gmail SMTP

2. **Gmail blocks the email**
   - Solution: Use App Password, not regular password
   - Enable "Less secure app access" (if needed)

3. **Firewall blocking**
   - Solution: Allow port 25, 587, or 465 in firewall

---

## 🚀 Recommended Solution

For **development/testing on XAMPP:**
1. Check error log for the code: `C:\xampp\php\logs\php_error_log`
2. Use the code from the log to test password reset

For **production:**
1. Use Gmail SMTP with PHPMailer (Option 2)
2. Or use a professional email service like SendGrid, Mailgun, or AWS SES

---

## 📝 Quick Setup for Gmail (5 Minutes)

1. **Install PHPMailer:**
   ```bash
   composer require phpmailer/phpmailer
   ```

2. **Get Gmail App Password:**
   - Google Account → Security → App Passwords
   - Generate new password
   - Copy it

3. **Update Email Settings:**
   Edit the email configuration in `forgot_password.php` or create dedicated config file

4. **Test It:**
   - Request password reset
   - Check your email!

---

## ✅ Current Status

✅ Email sending code is implemented  
✅ Code is saved to database  
✅ Code is logged to error log  
✅ HTML email template created  
⚠️ Requires SMTP configuration to actually deliver emails  

---

**Need help?** Check the error log file first - the verification code is being logged there for testing purposes!


