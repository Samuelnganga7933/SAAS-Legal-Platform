<?php

namespace App\Notifications;

use App\Models\PaymentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected PaymentRequest $paymentRequest;

    public function __construct(PaymentRequest $paymentRequest)
    {
        $this->paymentRequest = $paymentRequest;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Request - ' . config('app.name'))
            ->greeting('Payment Request')
            ->line('You have received a payment request from ' . config('app.name') . '.')
            ->line('Amount: ' . number_format($this->paymentRequest->amount, 2) . ' USD')
            ->line('Description: ' . ($this->paymentRequest->description ?? 'Consultation Payment'))
            ->line('Expires: ' . $this->paymentRequest->expires_at->format('M d, Y'))
            ->action('Pay Now', $this->paymentRequest->getPaymentUrl())
            ->line('If you have any questions, please reply to this email.')
            ->salutation('Thank you for choosing ' . config('app.name'));
    }
}
