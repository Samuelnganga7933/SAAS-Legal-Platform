<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentConfirmationNotification extends Notification implements ShouldQueue
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
            ->subject('Payment Confirmation - ' . config('app.name'))
            ->greeting('Payment Successful!')
            ->line('Thank you for your payment. Here are the details:')
            ->line('Payment ID: ' . $this->payment->stripe_payment_intent_id)
            ->line('Amount: ' . $this->payment->getFormattedAmount())
            ->line('Date: ' . $this->payment->paid_at->format('M d, Y H:i:s'))
            ->line('Status: ' . ucfirst($this->payment->status))
            ->action('View Payment Details', route('payments.status', $this->payment))
            ->line('If you have any questions, please contact us.')
            ->salutation('Thank you for choosing ' . config('app.name'));
    }
}
