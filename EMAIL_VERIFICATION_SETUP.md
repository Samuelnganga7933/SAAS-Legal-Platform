# Email Verification & Bot Protection Setup Guide

## Overview
This guide explains the email verification system with encrypted tokens and reCAPTCHA bot protection that have been implemented in the Le Nium Advisors Laravel application.

## Features Implemented

### 1. Email Verification with Encrypted Tokens
- Users must verify their email address before logging in
- Verification tokens are encrypted for security
- Tokens expire after 24 hours
- Users can request a new verification email

### 2. reCAPTCHA v3 Bot Protection
- Protects registration and login forms from bot attacks
- Silent verification (no checkbox needed for v3)
- Configurable score threshold (default: 0.5)
- Fallback to allow requests if reCAPTCHA is not configured (development only)

## Database Changes

A new migration has been created: `database/migrations/2026_03_03_000000_add_email_verification_to_users_table.php`

New columns added to `users` table:
- `email_verification_token` - Encrypted token for verification
- `email_verification_token_expires_at` - Token expiration timestamp
- `email_verified` - Boolean flag indicating verification status

Run the migration:
```bash
php artisan migrate
```

## Environment Configuration

Add the following to your `.env` file:

### Email Configuration (for sending verification emails)
```env
MAIL_MAILER=smtp
MAIL_SCHEME=tls
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_FROM_ADDRESS="noreply@leniumadvisors.com"
MAIL_FROM_NAME="Le Nium Advisors"
```

**Options:**
- **Development/Testing**: Use [Mailtrap](https://mailtrap.io/) (free account)
- **Production**: Use SendGrid, AWS SES, or your email service provider

### reCAPTCHA Configuration
1. Visit [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
2. Create a new site with:
   - Type: reCAPTCHA v3
   - Domain: your-domain.com
3. Copy your Site Key and Secret Key

Add to `.env`:
```env
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here
RECAPTCHA_SCORE_THRESHOLD=0.5
```

**Score Threshold** (0-1):
- 1.0 = Very likely legitimate
- 0.5 = Default (balanced)
- 0.0 = Very likely bot

## How It Works

### Registration Flow
1. User fills registration form with reCAPTCHA protection
2. `AuthController::register()` validates input and verifies reCAPTCHA
3. User account is created with `email_verified = false`
4. Encrypted verification token is generated and stored
5. `VerifyEmailNotification` sends verification email with link
6. User is redirected to `/email/verify` page

### Email Verification Flow
1. User receives email with verification link containing the plain token
2. User clicks link, which navigates to `/email/verify/{token}`
3. `AuthController::verifyEmail()` processes the token
4. Token is decrypted and compared with stored token
5. If valid and not expired:
   - Email marked as verified
   - Token is cleared from database
   - User is logged in
   - Redirected to account-created page
6. If invalid/expired:
   - Error message shown
   - User can request new verification email

### Login Flow
1. User enters credentials with reCAPTCHA protection
2. `AuthController::login()` verifies reCAPTCHA
3. Checks if user's email is verified
4. If not verified, prevents login and directs to verification page
5. If verified, logs user in normally

## Key Files

### Models
- [app/Models/User.php](../../app/Models/User.php) - Updated with email verification fields

### Services
- [app/Services/EmailVerificationService.php](../../app/Services/EmailVerificationService.php) - Handles token generation, encryption, and verification
- [app/Services/RecaptchaService.php](../../app/Services/RecaptchaService.php) - Handles reCAPTCHA verification with Google

### Controllers
- [app/Http/Controllers/AuthController.php](../../app/Http/Controllers/AuthController.php) - Updated with verification methods

### Notifications
- [app/Notifications/VerifyEmailNotification.php](../../app/Notifications/VerifyEmailNotification.php) - Email template for verification link

### Views
- [resources/views/auth/register.blade.php](../../resources/views/auth/register.blade.php) - Registration form with reCAPTCHA
- [resources/views/auth/login.blade.php](../../resources/views/auth/login.blade.php) - Login form with reCAPTCHA
- [resources/views/auth/verify-email.blade.php](../../resources/views/auth/verify-email.blade.php) - Email verification pending page

### Routes
- [routes/web.php](../../routes/web.php) - Email verification routes:
  - `GET /email/verify` - Verification pending page
  - `GET /email/verify/{token}` - Verify email with token
  - `POST /email/resend` - Resend verification email

### Configuration
- [config/services.php](../../config/services.php) - reCAPTCHA configuration

## API Endpoints

### Email Verification Endpoints

#### 1. Verify Email
```
GET /email/verify/{token}?email=user@example.com
```
**Query Parameters:**
- `token` (required) - Plain token from email link
- `email` (required) - User's email address

**Response:**
- Success: Logs user in, redirects to account-created page
- Failure: Shows error, redirects to verification page

#### 2. Resend Verification Email
```
POST /email/resend
```
**Body:**
```json
{
  "email": "user@example.com"
}
```

**Response:**
- Success: 302 redirect with success message
- Error: 302 redirect with error message

#### 3. Verification Pending Page
```
GET /email/verify
```
**Response:** Shows verification pending page with resend option

## Testing

### Manual Testing

1. **Register new account:**
   - Visit `/register`
   - Fill form and submit
   - Check email (Mailtrap inbox for testing)
   - Click verification link

2. **Resend verification email:**
   - Go to `/email/verify`
   - Enter email address
   - Submit form

3. **Try logging in without verifying:**
   - Register but don't verify
   - Try to login with those credentials
   - Should see error message

### Automated Testing
Create a test in `tests/Feature/` to verify:
```php
// Test email verification flow
public function test_user_must_verify_email_before_login()
{
    // Create user without verifying
    // Attempt login
    // Assert redirected to verification page
}
```

## Security Considerations

### Token Encryption
- Tokens are encrypted using Laravel's built-in encryption (AES-256)
- Uses APP_KEY from .env
- Tokens are stored encrypted in database

### Token Expiration
- Tokens expire after 24 hours (configurable in EmailVerificationService)
- Old tokens are automatically cleared when user requests new verification

### reCAPTCHA
- v3 provides invisible bot protection
- Scores analyzed server-side
- Works without user interaction
- Recommended minimum score: 0.4-0.6

### Email Security
- Links include both token and email for verification
- Token is single-use (cleared after verification)
- Expired tokens cannot be used

## Troubleshooting

### Email Not Sending
1. Check `.env` MAIL configuration
2. Verify Mailtrap credentials (if using Mailtrap)
3. Check Laravel logs: `storage/logs/`
4. Test: `php artisan tinker` then `Mail::raw('test', function($m) {})`

### reCAPTCHA Issues
1. Verify site key and secret key are correct
2. Check domain is registered in reCAPTCHA console
3. Allow development/localhost if testing locally
4. Clear browser cache

### Verification Link Not Working
1. Ensure token format is preserved in email link
2. Check token hasn't expired (24 hours)
3. Verify APP_KEY is consistent (used for decryption)
4. Check logs for decryption errors

## Customization

### Change Token Expiration Time
Edit [app/Services/EmailVerificationService.php](../../app/Services/EmailVerificationService.php):
```php
protected int $tokenExpirationHours = 24; // Change to desired hours
```

### Change reCAPTCHA Score Threshold
Edit `.env`:
```env
RECAPTCHA_SCORE_THRESHOLD=0.4  # Lower = stricter, Higher = lenient
```

### Customize Email Template
Edit [app/Notifications/VerifyEmailNotification.php](../../app/Notifications/VerifyEmailNotification.php) to change:
- Subject line
- Email greeting
- Content and call-to-action text

## Future Enhancements

Potential improvements:
1. SMS verification as alternative to email
2. Two-factor authentication (2FA)
3. Verification email resend limits
4. Account lockout after multiple failed verifications
5. Admin dashboard to manage user verification status
6. Email template customization in admin panel

## Support

For issues or questions, refer to:
- Laravel Documentation: https://laravel.com/docs
- Google reCAPTCHA: https://developers.google.com/recaptcha
- Mailtrap: https://mailtrap.io/
