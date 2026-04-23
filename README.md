# ⚖️ Legal Operations & Workflow Platform

A full-featured SaaS platform built for law firms and legal advisors to manage cases, clients, billing, documents, and team operations — all in one place.

---

## 🚀 Features

### 👥 Multi-Role System
- **Admin** — Full platform control, team management, analytics
- **Worker (Attorney/Staff)** — Case management, task board, time logging, messaging
- **Client** — Case tracking, document access, billing, messaging portal

### 📁 Case Management
- Create, assign, and track legal cases
- Case notes, status updates, and history
- Client-case linking with access control

### 💰 Billing & Payments
- Payment requests and invoicing
- Payment confirmation, failure, and refund notifications
- Subscription plans with upgrade/downgrade support
- Membership cancellation flow

### 📅 Calendar & Scheduling
- Event creation and management
- Livewire-powered interactive calendar view

### 💬 Messaging
- Direct messaging between clients, workers, and admins
- Broadcast messaging (admin to all users)
- Email notifications for new messages

### 📂 Document Management
- Upload and manage client documents
- Worker document creation and indexing
- Access control via policies

### 🤝 CRM
- Contact management with interaction tracking
- Kanban-style CRM board (Livewire)
- Contact detail and edit views

### 🔐 Authentication & Security
- Email/password registration with email verification
- Google OAuth login
- Role-based middleware and permission system
- Security headers middleware
- Rate limiting and throttle protection
- reCAPTCHA integration

### 📊 Analytics
- Worker analytics dashboard
- Admin billing and payments dashboard
- Time logging and reporting

### 🛠️ Team Management
- Invite team members via email
- Role assignment and permission management
- Livewire team manager interface

---

## 🧱 Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 11 |
| Frontend | Blade, Livewire, Tailwind CSS |
| Database | MySQL |
| Auth | Laravel Auth + Google OAuth (Socialite) |
| Payments | Stripe / M-Pesa (via PaymentService) |
| Real-time | Livewire |
| Email | Laravel Mail + SMTP |
| Queue | Laravel Queue (Jobs) |
| Storage | Laravel Filesystem (local/S3) |

---

## ⚙️ Installation

### Requirements
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL
- Laravel 11

### Steps

```bash
# Clone the repository
git clone https://github.com/Samuelnganga7933/SAAS-Legal-Platform.git
cd SAAS-Legal-Platform

# Install PHP dependencies
composer install

# Install JS dependencies
npm install && npm run build

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your .env (database, mail, payment keys, Google OAuth)

# Run migrations and seeders
php artisan migrate --seed

# Start the server
php artisan serve
```

---

## 🔑 Environment Variables

Key variables to configure in your `.env`:

```env
APP_NAME="Legal Platform"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_USERNAME=your_mail_username
MAIL_PASSWORD=your_mail_password

GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret

STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret

RECAPTCHA_SITE_KEY=your_recaptcha_site_key
RECAPTCHA_SECRET_KEY=your_recaptcha_secret_key
```

---

## 👤 Default Seeded Users

After running `php artisan migrate --seed`:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| CEO | ceo@example.com | password |
| Test Client | client@example.com | password |

> ⚠️ Change these credentials immediately in production.

---

## 📁 Project Structure

```
app/
├── Http/Controllers/     # All controllers (Admin, Client, Worker, Auth, etc.)
├── Livewire/             # Livewire components (Calendar, CRM, Tasks, Messaging)
├── Models/               # Eloquent models
├── Services/             # PaymentService, EmailVerificationService
├── Notifications/        # Payment and email notifications
├── Policies/             # Authorization policies
resources/
├── views/
│   ├── admin/            # Admin dashboard views
│   ├── client/           # Client portal views
│   ├── worker/           # Worker dashboard views
│   ├── auth/             # Login, register, verify
│   └── livewire/         # Livewire component views
database/
├── migrations/           # All database migrations
└── seeders/              # Admin, CEO, and test user seeders
```

---

## 🛡️ Security

- All routes protected by role-based middleware
- CSRF protection on all forms
- Security headers via middleware
- Input validation on all requests
- Email verification required before access
- Rate limiting on auth and API routes

---

## 📜 License

This project is proprietary software. All rights reserved.

---

## 🙋 Support

For support or inquiries, please contact the development team.
