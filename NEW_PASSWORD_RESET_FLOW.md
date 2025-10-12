# New Password Reset Flow - Email Code Verification

## Overview
The password reset system has been completely redesigned to use a 6-digit verification code sent to the user's email instead of a direct reset link. This provides better security and user experience.

## New Flow

### 1. **User Requests Password Reset**
- User clicks "Forgot Password?" on login page
- User enters their email address
- System validates email format and existence

### 2. **Code Generation and Storage**
- System generates a 6-digit random code (000000-999999)
- Code is stored in `password_resets` table with 15-minute expiration
- User is redirected to code verification page

### 3. **Code Verification**
- User sees a page with 6 individual input fields
- User enters the 6-digit code received via email
- System validates the code (exists and not expired)
- If valid, user is redirected to password reset page

### 4. **Password Reset**
- User enters new password and confirmation
- System validates password requirements and match
- Password is hashed and updated in database
- Reset code is deleted after successful reset

### 5. **Login with New Password**
- User can now login with the new password
- System redirects to appropriate dashboard

## Files Created/Modified

### New Files:
- `verify_code.php` - Code verification page with 6 input fields
- `verify_code_handler.php` - Processes code verification
- `resend_code.php` - Handles code resending
- `test_codes.php` - Development testing page to view generated codes

### Modified Files:
- `forgot_password.php` - Updated to generate codes instead of tokens
- `reset_password.php` - Updated to work with verified users only
- `reset_passwordhandler.php` - Updated to work with user ID instead of tokens

## Security Features

- ✅ 6-digit numeric codes (easier to enter than long tokens)
- ✅ 15-minute expiration (shorter than previous 1-hour tokens)
- ✅ One active code per user (UNIQUE constraint)
- ✅ Session-based verification (prevents direct URL access)
- ✅ Code deletion after successful reset
- ✅ Input validation and sanitization
- ✅ SQL injection protection

## User Experience Improvements

- ✅ Clean 6-digit code input interface
- ✅ Auto-focus and navigation between input fields
- ✅ Backspace handling for easy correction
- ✅ Resend code functionality
- ✅ Clear email display on verification page
- ✅ Visual feedback for all actions

## Development Testing

### View Generated Codes:
Visit `test_codes.php` to see:
- Current session codes
- Active codes in database
- Code expiration times
- Associated user information

### Test Flow:
1. Go to `index.php`
2. Click "Forgot Password?"
3. Enter email: `admin@nu.edu.ph`
4. Click "Send Reset Link"
5. You'll be redirected to `verify_code.php`
6. Check `test_codes.php` for the generated code
7. Enter the 6-digit code
8. Set new password
9. Login with new password

## Database Structure

The `password_resets` table structure remains the same but now stores 6-digit codes in the `token` field instead of long random strings.

## Production Considerations

For production deployment:
1. Replace the development email function with proper email service (PHPMailer, SendGrid, etc.)
2. Remove or secure the `test_codes.php` file
3. Consider adding rate limiting for code requests
4. Add email templates for better user experience
5. Consider SMS backup for code delivery

## Expected Behavior

1. ✅ User enters email → Gets redirected to code verification page
2. ✅ User enters 6-digit code → Gets redirected to password reset page
3. ✅ User sets new password → Gets redirected to login page
4. ✅ User can login with new password
5. ✅ Codes expire after 15 minutes
6. ✅ Used codes are deleted after successful reset
7. ✅ Users can resend codes if needed

## Error Handling

- Invalid email format
- Email not found in database
- Invalid or expired codes
- Session expiration
- Password validation errors
- Database connection issues

All errors are properly handled with user-friendly messages and appropriate redirects.
