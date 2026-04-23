# Setup Guide: Interactive Server Control & Google Sign-In

## 1. Interactive Server Control

You can now use the interactive server command to manage your Laravel development server:

```bash
php artisan serve:interactive
```

This command offers:
- **Start Server**: Start the development server with custom host/port
- **Stop Server**: Stop the running server
- **Restart Server**: Restart the server
- **Status Check**: Check if the server is running on a specific port

### Usage Example:
```bash
cd "c:\xampp\htdocs\Le Nium Advisors website\nium-advisors-laravel"
php artisan serve:interactive
```

---

## 2. Google Sign-In Setup

### Step 1: Get Google OAuth Credentials

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project called "Le Nium Advisors"
3. Enable the Google+ API:
   - Go to "APIs & Services" > "Library"
   - Search for "Google+ API"
   - Click "Enable"

4. Create OAuth 2.0 Credentials:
   - Go to "APIs & Services" > "Credentials"
   - Click "Create Credentials" > "OAuth client ID"
   - Choose "Web application"
   - Add these to Authorized redirect URIs:
     - `http://localhost:8001/auth/google/callback`
     - Your production domain when ready
   - Copy the **Client ID** and **Client Secret**

### Step 2: Update Environment Variables

Edit `.env` file and update these values:

```env
GOOGLE_CLIENT_ID=your_actual_client_id_from_google
GOOGLE_CLIENT_SECRET=your_actual_client_secret_from_google
GOOGLE_REDIRECT_URI=http://localhost:8001/auth/google/callback
```

### Step 3: Update Database

Run the migration to add the `google_id` column:

```bash
php artisan migrate
```

### Step 4: Install Dependencies

Make sure Socialite is installed:

```bash
composer require laravel/socialite
```

### Step 5: Test the Integration

1. Start your server:
   ```bash
   php artisan serve:interactive
   ```
   Select "Start Server" and choose port 8001

2. Visit `http://localhost:8001`

3. Click "Sign in with Google" button

4. You'll be redirected to Google login

5. After successful authentication, you'll be logged in and redirected to dashboard

---

## Files Modified/Created

### Created Files:
- `app/Console/Commands/ServeInteractive.php` - Interactive server command
- `app/Http/Controllers/GoogleAuthController.php` - Google OAuth handler
- `database/migrations/2026_02_11_000000_add_google_id_to_users_table.php` - Migration for google_id column

### Modified Files:
- `routes/web.php` - Added Google OAuth routes
- `app/Models/User.php` - Added google_id to fillable array
- `config/services.php` - Added Google service configuration
- `.env` - Added Google OAuth environment variables
- `resources/views/welcome.blade.php` - Added "Sign in with Google" button

---

## Troubleshooting

### "Server is already running" Error
Use the interactive command to stop it first:
```bash
php artisan serve:interactive
```
Select "Stop Server"

### Google Login Redirect Issues
- Verify your Redirect URI in Google Cloud Console matches exactly:
  `http://localhost:8001/auth/google/callback`
- Make sure `APP_URL` in `.env` is set to `http://localhost:8001`

### Database Issues
If you get migration errors:
```bash
php artisan migrate:fresh  # WARNING: This resets your database
```

---

## Notes

✅ Google users are automatically created in the database
✅ Email is verified upon Google login
✅ Subsequent logins use existing account
✅ Random password is generated for OAuth users (optional to set custom)

