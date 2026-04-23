<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientMessagingController;
use App\Http\Controllers\AdminMessagingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\MessagingController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WorkerDashboardController;
use App\Http\Controllers\WorkerSettingsController;
use App\Http\Controllers\WorkerBillingController;
use App\Http\Controllers\WorkerAnalyticsController;
use App\Http\Controllers\WorkerMessagingController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClientBillingController;
use App\Http\Controllers\AdminBillingController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\SettingsController;

// Health Check Routes (public, for monitoring/load balancers)
Route::get('/health', [HealthCheckController::class, 'check'])->name('health.check');
Route::get('/health/live', [HealthCheckController::class, 'live'])->name('health.live');
Route::get('/health/ready', [HealthCheckController::class, 'ready'])->name('health.ready');

// Public Routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/disclaimer', [PageController::class, 'disclaimer'])->name('disclaimer');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/login', [AuthController::class, 'login'])->name('login.verify');

Route::post('/register', [AuthController::class, 'register'])->name('register.store');

// Email Verification Routes
Route::get('/email/verify', [AuthController::class, 'showVerificationPending'])
    ->name('email-verification.pending');

Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])
    ->name('email-verification.verify');

Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])
    ->name('email-verification.resend');

// Modal Signup Routes (for homepage signup modal)
Route::post('/auth/register/initial', function () {
    // Initial signup form submission - sends verification code to email
    return response()->json([
        'success' => true,
        'message' => 'Verification code sent to your email'
    ]);
})->name('auth.register.initial');

Route::post('/auth/register/verify', function () {
    // Email verification code submission
    return response()->json([
        'success' => true,
        'message' => 'Email verified successfully'
    ]);
})->name('auth.register.verify');

// Password Reset Routes
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function () {
    // Handle password reset email sending
})->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/reset-password', function () {
    // Handle password update
})->name('password.update');

Route::get('/account-created', function () {
    return view('auth.account-created');
})->middleware('auth')->name('account.created');

// Team Invitation Routes
Route::get('/accept-invitation/{token}', [InvitationController::class, 'show'])->name('invitation.show');
Route::post('/accept-invitation/{token}', [InvitationController::class, 'accept'])->name('invitation.accept');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Comments Routes (requires authentication and rate limiting)
Route::middleware(['auth', 'throttle:comments'])->group(function () {
    Route::post('/comments', [CommentsController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{id}', [CommentsController::class, 'destroy'])->name('comments.destroy');
});

// Payment Routes (Client-only - for client subscriptions and payments)
Route::prefix('payments')->name('payments.')->middleware(['auth', 'ensure.user.is.client'])->group(function () {
    // Payment intent creation
    Route::post('/create-intent', [PaymentController::class, 'createPaymentIntent'])->name('create-intent');
    Route::post('/confirm', [PaymentController::class, 'confirmPayment'])->name('confirm');
    Route::get('/{payment}/status', [PaymentController::class, 'checkPaymentStatus'])->name('status');
    Route::get('/success', [PaymentController::class, 'paymentSuccess'])->name('success');
    Route::get('/failure', [PaymentController::class, 'paymentFailure'])->name('failure');
    
    // Client payment management
    Route::post('/{payment}/refund', [PaymentController::class, 'refundPayment'])->name('refund');
    Route::post('/{payment}/cancel', [PaymentController::class, 'cancelPayment'])->name('cancel');
    Route::get('/history', [PaymentController::class, 'getPaymentHistory'])->name('history');
    
    // Consultation payment form - clients only
    Route::get('/consultation/form', [PaymentController::class, 'showConsultationPaymentForm'])->name('consultation.form');
});

// Webhook Routes
Route::prefix('webhooks')->name('webhooks.')->group(function () {
    Route::post('/stripe', [WebhookController::class, 'handleStripeWebhook'])->name('stripe');
});

// Client Portal Routes (requires authentication and client role - not admin)
Route::middleware(['auth', 'ensure.user.is.client'])->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/documents', function () {
        return view('client.documents');
    })->name('documents');
    
    Route::get('/tasks', function () {
        return view('client.tasks');
    })->name('tasks');
    
    Route::get('/calendar', function () {
        return view('calendar.index');
    })->name('calendar');
    
    Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ClientBillingController::class, 'index'])->name('index');
        Route::get('/upgrade', [\App\Http\Controllers\ClientBillingController::class, 'upgradePlan'])->name('upgrade');
        Route::post('/upgrade', [\App\Http\Controllers\ClientBillingController::class, 'processPlanUpgrade'])->name('upgrade.process');
        Route::post('/cancel', [\App\Http\Controllers\ClientBillingController::class, 'cancelSubscription'])->name('cancel');
        Route::get('/invoices/{invoice}/download', [\App\Http\Controllers\ClientBillingController::class, 'downloadInvoice'])->name('download-invoice');
        Route::get('/invoices/{invoice}/pay', [\App\Http\Controllers\ClientBillingController::class, 'payInvoice'])->name('pay-invoice');
        Route::get('/payment-method/add', [\App\Http\Controllers\ClientBillingController::class, 'addPaymentMethod'])->name('add-payment-method');
        Route::get('/payment-method/update', [\App\Http\Controllers\ClientBillingController::class, 'updatePaymentMethod'])->name('update-payment-method');
        Route::post('/payment-method/update', [\App\Http\Controllers\ClientBillingController::class, 'processPaymentMethodUpdate'])->name('update-payment-method.process');
    });
    
    Route::get('/membership', function () {
        return view('client.membership');
    })->name('membership');
    
    Route::get('/membership/cancel', function () {
        return view('client.membership-cancel');
    })->name('membership.cancel');
    
    Route::post('/membership/cancel', function () {
        // Handle cancellation request submission
        return redirect()->route('membership.cancel.confirmation');
    })->name('membership.cancel.submit');
    
    Route::get('/settings', function () {
        return view('client.settings');
    })->name('settings');
    
    Route::put('/settings', function () {
        // Handle settings update
    })->name('settings.update');
    
    // Client Messaging Routes
    Route::get('/messages/inbox', [ClientMessagingController::class, 'inbox'])->name('client.messages.inbox');
    Route::get('/messages/{message}', [ClientMessagingController::class, 'show'])->name('client.messages.show');
    Route::get('/messages/create', [ClientMessagingController::class, 'createMessage'])->name('client.messages.create');
    Route::post('/messages/send', [ClientMessagingController::class, 'sendMessage'])->name('client.messages.send');
    
    // Document Routes
    Route::get('/documents/{id}/view', [DocumentController::class, 'view'])->name('documents.view');
    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');
    
    // Cases Management Routes
    Route::resource('cases', CaseController::class);
    Route::post('/cases/{case}/withdraw', [CaseController::class, 'withdraw'])->name('cases.withdraw');
    
    // Unified Messaging Routes
    Route::prefix('messaging')->name('messaging.')->group(function () {
        Route::get('/inbox', [MessagingController::class, 'inbox'])->name('inbox');
        Route::get('/sent', [MessagingController::class, 'sent'])->name('sent');
        Route::get('/{message}', [MessagingController::class, 'show'])->name('show');
        Route::post('/send', [MessagingController::class, 'store'])->name('store');
        Route::post('/{message}/read', [MessagingController::class, 'markAsRead'])->name('mark-read');
        Route::delete('/{message}', [MessagingController::class, 'destroy'])->name('destroy');
        Route::get('/{message}/status', [MessagingController::class, 'deliveryStatus'])->name('delivery-status');
        Route::get('/count/unread', [MessagingController::class, 'unreadCount'])->name('unread-count');
    });
});

// Worker Portal Routes (requires authentication and worker role)
Route::middleware(['auth', 'ensure.user.is.worker'])->prefix('worker')->name('worker.')->group(function () {
    Route::get('/dashboard', [WorkerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [WorkerDashboardController::class, 'getStats'])->name('dashboard.stats');
    
    Route::prefix('cases')->name('cases.')->group(function () {
        Route::get('/', function () { return view('worker.cases.index'); })->name('index');
        Route::get('/{case}', function () { return view('worker.cases.show'); })->name('show');
        Route::post('/{case}/reassign', function () { })->name('reassign');
        Route::post('/{case}/update-status', function () { })->name('update-status');
    });
    
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::get('/board', [TaskController::class, 'board'])->name('board');
        Route::get('/create', [TaskController::class, 'create'])->name('create');
        Route::post('/', [TaskController::class, 'store'])->name('store');
        Route::get('/{task}', [TaskController::class, 'show'])->name('show');
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
        Route::put('/{task}', [TaskController::class, 'update'])->name('update');
        Route::post('/{task}/status', [TaskController::class, 'updateStatus'])->name('update-status');
        Route::post('/bulk/update', [TaskController::class, 'bulkUpdate'])->name('bulk-update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
    });
    
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/{case}', [DocumentController::class, 'index'])->name('index');
        Route::get('/{case}/create', [DocumentController::class, 'create'])->name('create');
        Route::post('/{case}', [DocumentController::class, 'store'])->name('store');
        Route::get('/{document}/view', [DocumentController::class, 'view'])->name('view');
        Route::get('/{document}/download', [DocumentController::class, 'download'])->name('download');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy');
    });
    
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/inbox', [WorkerMessagingController::class, 'inbox'])->name('inbox');
        Route::get('/{participantId}', [WorkerMessagingController::class, 'show'])->name('show');
        Route::post('/send/{conversationId}', [WorkerMessagingController::class, 'send'])->name('send');
        Route::get('/api/unread', [WorkerMessagingController::class, 'unreadCount'])->name('unread');
        Route::post('/{message}/read', [WorkerMessagingController::class, 'markAsRead'])->name('mark-read');
        Route::delete('/{message}', [WorkerMessagingController::class, 'destroy'])->name('destroy');
        Route::get('/api/new', [WorkerMessagingController::class, 'getNew'])->name('new');
    });
    
    Route::prefix('time-log')->name('time-log.')->group(function () {
        Route::get('/current', function () { })->name('current');
        Route::post('/start', function () { })->name('start');
        Route::post('/stop', function () { })->name('stop');
        Route::post('/create', function () { })->name('create');
        Route::delete('/{timeLog}', function () { })->name('destroy');
    });
    
    Route::get('/billing', [WorkerBillingController::class, 'dashboard'])->name('billing');
    Route::get('/billing/upgrade', [WorkerBillingController::class, 'upgrade'])->name('billing.upgrade');
    Route::post('/billing/upgrade', [WorkerBillingController::class, 'processUpgrade'])->name('billing.process-upgrade');
    
    Route::get('/analytics', [WorkerAnalyticsController::class, 'dashboard'])->name('analytics');
    Route::get('/analytics/export-pdf', [WorkerAnalyticsController::class, 'exportPdf'])->name('analytics.export-pdf');
    Route::get('/analytics/export-excel', [WorkerAnalyticsController::class, 'exportExcel'])->name('analytics.export-excel');
    
    Route::get('/settings', [WorkerSettingsController::class, 'show'])->name('settings');
    Route::put('/settings/profile', [WorkerSettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/availability', [WorkerSettingsController::class, 'updateAvailability'])->name('settings.availability');
    Route::put('/settings/password', [WorkerSettingsController::class, 'updatePassword'])->name('settings.password');
    Route::put('/settings/notifications', [WorkerSettingsController::class, 'updateNotifications'])->name('settings.notifications');
});

// Admin Portal Routes (requires authentication and admin role)
Route::middleware(['auth', 'ensure.user.is.admin'])->group(function () {
    Route::get('/admin', function () {
        return redirect()->route('admin.dashboard');
    });
    
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::get('/admin/clients', function () {
        return view('admin.clients');
    })->name('admin.clients');
    
    Route::get('/admin/cancellation-requests', function () {
        return view('admin.cancellation-requests');
    })->name('admin.cancellation-requests');
    
    Route::post('/admin/cancellation/{id}/approve', function () {
        // Handle approval
    })->name('admin.cancellation.approve');
    
    Route::post('/admin/cancellation/{id}/decline', function () {
        // Handle decline
    })->name('admin.cancellation.decline');
    
    Route::get('/admin/cases', function () {
        return view('admin.cases');
    })->name('admin.cases');
    
    Route::get('/admin/documents', function () {
        return view('admin.documents');
    })->name('admin.documents');
    
    Route::get('/admin/team', function () {
        return view('admin.team');
    })->name('admin.team');
    
    Route::prefix('admin/billing')->name('admin.billing.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminBillingController::class, 'index'])->name('index');
        Route::post('/subscriptions/{user}/cancel', [\App\Http\Controllers\AdminBillingController::class, 'cancelSubscription'])->name('subscriptions.cancel');
        Route::post('/invoices/{invoice}/mark-paid', [\App\Http\Controllers\AdminBillingController::class, 'markInvoicePaid'])->name('invoices.mark-paid');
        Route::post('/invoices/{invoice}/send-reminder', [\App\Http\Controllers\AdminBillingController::class, 'sendPaymentReminder'])->name('invoices.send-reminder');
    });
    
    Route::get('/admin/billing', function () {
        return redirect()->route('admin.billing.index');
    })->name('admin.billing');
    
    Route::get('/admin/settings', function () {
        return view('admin.settings');
    })->name('admin.settings');

    // Payment Management Routes
    Route::get('/admin/payments', [PaymentController::class, 'adminDashboard'])->name('admin.payments.dashboard');
    Route::get('/admin/payments/export', [PaymentController::class, 'exportPayments'])->name('admin.payments.export');
    Route::post('/admin/payments/{payment}/refund', [PaymentController::class, 'adminRefund'])->name('admin.payments.refund');
    Route::get('/api/admin/payments', [PaymentController::class, 'getPaymentsData'])->name('admin.api.payments');
    
    // Export Routes
    Route::get('/admin/export/clients/csv', [AdminController::class, 'exportClientsCSV'])->name('admin.export.clients.csv');
    Route::get('/admin/export/clients/excel', [AdminController::class, 'exportClientsExcel'])->name('admin.export.clients.excel');
    Route::get('/admin/export/clients/pdf', [AdminController::class, 'exportClientsPDF'])->name('admin.export.clients.pdf');
    
    // API Routes for Admin
    Route::get('/api/admin/clients', [AdminController::class, 'getClients'])->name('admin.api.clients');
    
    // Admin Messaging Routes
    Route::prefix('/admin/messages')->name('admin.messages.')->group(function () {
        Route::get('/inbox', [AdminMessagingController::class, 'inbox'])->name('inbox');
        Route::get('/{message}', [AdminMessagingController::class, 'show'])->name('show');
        Route::get('/create/direct', [AdminMessagingController::class, 'createDirectMessage'])->name('create.direct');
        Route::post('/send/direct', [AdminMessagingController::class, 'sendDirectMessage'])->name('send.direct');
        Route::get('/create/broadcast', [AdminMessagingController::class, 'createBroadcast'])->name('create.broadcast');
        Route::post('/send/broadcast', [AdminMessagingController::class, 'sendBroadcast'])->name('send.broadcast');
        Route::get('/broadcasts', [AdminMessagingController::class, 'broadcastHistory'])->name('broadcasts');
    });

    // CRM Routes (Admin/CEO only)
    Route::prefix('crm')->name('crm.')->group(function () {
        Route::get('/', [CrmController::class, 'index'])->name('index');
        Route::get('/{contact}', [CrmController::class, 'show'])->name('show');
        Route::get('/{contact}/edit', [CrmController::class, 'edit'])->name('edit');
        Route::put('/{contact}', [CrmController::class, 'update'])->name('update');
        Route::delete('/{contact}', [CrmController::class, 'destroy'])->name('destroy');
        Route::post('/{contact}/interactions', [CrmController::class, 'logInteraction'])->name('log-interaction');
    });
});

// Settings Routes (All authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.update-password');
    Route::post('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.update-notifications');
    Route::post('/settings/appearance', [SettingsController::class, 'updateAppearance'])->name('settings.update-appearance');
    Route::post('/settings/integrations', [SettingsController::class, 'updateIntegrations'])->name('settings.update-integrations');
    Route::delete('/settings/account', [SettingsController::class, 'deleteAccount'])->name('settings.delete-account');
    Route::delete('/settings/reset-platform', [SettingsController::class, 'resetPlatform'])->name('settings.reset-platform');
});

// Google OAuth Routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('google.callback');
