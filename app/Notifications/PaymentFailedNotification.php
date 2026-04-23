<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Failed - Action Required')
            ->greeting('Payment Processing Failed')
            ->line('Unfortunately, we were unable to process your payment.')
            ->line('Error: ' . ($this->payment->failure_message ?? 'Please try again with a different payment method.'))
            ->line('Payment Amount: ' . $this->payment->getFormattedAmount())
            ->line('Payment ID: ' . $this->payment->stripe_payment_intent_id)
            ->action('Try Again', route('payments.consultation.form', [
                'amount' => $this->payment->amount,
                'email' => $this->payment->customer_email,
                'name' => $this->payment->customer_name,
            ]))
            ->line('If the issue persists, please contact our support team.')
            ->salutation('Thank you');
    }
}
