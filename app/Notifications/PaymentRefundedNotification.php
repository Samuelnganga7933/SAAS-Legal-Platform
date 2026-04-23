<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRefundedNotification extends Notification implements ShouldQueue
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
            ->subject('Refund Processed - ' . config('app.name'))
            ->greeting('Refund Processed Successfully')
            ->line('Your refund has been processed.')
            ->line('Refund Amount: ' . number_format($this->payment->refunded_amount, 2) . ' ' . $this->payment->currency)
            ->line('Original Payment ID: ' . $this->payment->stripe_payment_intent_id)
            ->line('Refund Date: ' . $this->payment->refunded_at->format('M d, Y H:i:s'))
            ->line('The refunded amount should appear in your account within 5-10 business days.')
            ->line('If you have any questions, please contact us.')
            ->salutation('Thank you');
    }
}
