# Payment Integration Setup Guide

## Overview
This Laravel application now includes a complete Stripe payment integration system for handling consultations and service payments. The integration supports payment processing, refunds, webhooks, and payment history tracking.

## Features

- **Payment Intent Creation**: Create Stripe payment intents for consultations and services
- **Payment Processing**: Handle payment confirmations and updates
- **Webhook Support**: Automatic payment status updates from Stripe webhooks
- **Refund Management**: Process refunds for successful payments
- **Payment History**: Track payment history per customer
- **Multiple Payment Types**: Support for different payment scenarios (consultations, services, etc.)
- **Secure & Encrypted**: Customer data is encrypted and securely stored
- **Admin Dashboard Integration**: View payment statistics and manage payments

## Installation & Setup

### 1. Install Stripe PHP Library

```bash
composer require stripe/stripe-php
```

### 2. Environment Variables

Add the following variables to your `.env` file:

```env
# Stripe Configuration
STRIPE_PUBLIC_KEY=pk_test_YOUR_PUBLIC_KEY_HERE
STRIPE_SECRET_KEY=sk_test_YOUR_SECRET_KEY_HERE
STRIPE_WEBHOOK_SECRET=whsec_YOUR_WEBHOOK_SECRET_HERE
STRIPE_CURRENCY=usd
```

**How to Get Your Stripe Keys:**
1. Go to [Stripe Dashboard](https://dashboard.stripe.com)
2. Navigate to "Developers" → "API Keys"
3. Copy your Publishable Key and Secret Key
4. For Webhook Secret: Go to "Developers" → "Webhooks" → Create a new endpoint with URL `yourdomain.com/webhooks/stripe`

### 3. Run Migrations

```bash
php artisan migrate
```

This will create the `payments` table with all necessary fields for payment tracking.

## Database Schema

### Payments Table

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| stripe_payment_intent_id | uuid | Unique Stripe payment intent ID |
| payment_type | string | Type of payment (consultation, service, etc.) |
| payable_id | bigint | ID of the related model (consultation, service, etc.) |
| payable_type | string | Type of related model (polymorphic) |
| user_id | bigint | Associated user ID (nullable) |
| customer_email | string (encrypted) | Customer email |
| customer_name | string (encrypted) | Customer name |
| amount | decimal(15,2) | Payment amount |
| currency | string | Currency code (default: USD) |
| status | enum | pending, processing, succeeded, failed, refunded, canceled |
| payment_method | string | Payment method used (card, etc.) |
| stripe_response | json | Full Stripe response data |
| failure_message | string | Error message if payment failed |
| paid_at | timestamp | Timestamp when payment succeeded |
| refunded_at | timestamp | Timestamp when refund processed |
| refunded_amount | decimal(15,2) | Amount refunded (if any) |
| description | text | Payment description |
| metadata | json | Additional metadata |
| ip_address | ipAddress | Customer IP address |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |
| deleted_at | timestamp | Soft delete timestamp |

## API Routes

### Payment Management Endpoints

#### Create Payment Intent
```
POST /payments/create-intent
```
**Parameters:**
- `amount` (required, decimal): Payment amount
- `email` (required, string): Customer email
- `customer_name` (required, string): Customer name
- `description` (optional, string): Payment description
- `payment_type` (required, string): Type of payment (consultation, service)
- `payable_id` (optional, integer): Related entity ID
- `payable_type` (optional, string): Related entity type
- `metadata` (optional, json): Additional metadata

**Response:**
```json
{
    "success": true,
    "clientSecret": "pi_xxxxx_secret_xxxxx",
    "paymentId": 1,
    "amount": 99.99,
    "currency": "USD"
}
```

#### Confirm Payment
```
POST /payments/confirm
```
**Parameters:**
- `payment_id` (required): Payment ID to confirm
- `payment_method_id` (required): Stripe payment method ID

#### Check Payment Status
```
GET /payments/{payment}/status
```

#### Refund Payment (Authenticated)
```
POST /payments/{payment}/refund
```
**Parameters:**
- `amount` (optional, decimal): Amount to refund (defaults to full amount)

#### Cancel Payment (Authenticated)
```
POST /payments/{payment}/cancel
```
**Parameters:**
- `reason` (optional): Cancellation reason

#### Get Payment History (Authenticated)
```
GET /payments/history?email=customer@example.com&limit=10
```

### Webhook Endpoint
```
POST /webhooks/stripe
```

## Configuration

### Services Configuration (`config/services.php`)

The Stripe configuration is already added to `config/services.php`:

```php
'stripe' => [
    'public' => env('STRIPE_PUBLIC_KEY'),
    'secret' => env('STRIPE_SECRET_KEY'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    'currency' => env('STRIPE_CURRENCY', 'usd'),
],
```

## Models & Relationships

### Payment Model
Located at `app/Models/Payment.php`

**Key Methods:**
- `markAsSucceeded()`: Mark payment as successful
- `markAsFailed($message)`: Mark payment as failed
- `markAsRefunded($amount)`: Mark payment as refunded
- `isSucceeded()`: Check if payment succeeded
- `isPending()`: Check if payment is pending
- `hasFailed()`: Check if payment failed
- `getAmountInCents()`: Get amount in cents for Stripe
- `getFormattedAmount()`: Get formatted currency string

**Relationships:**
- `payable()`: Polymorphic relationship to any model
- `user()`: Belongs to User model

**Scopes:**
- `succeeded()`: Get only successful payments
- `pending()`: Get pending payments
- `failed()`: Get failed payments

### Consultation Model
Updated to include payment relationships:
- `payments()`: Get all payments for consultation
- `succeededPayments()`: Get successful payments
- `isPaid()`: Check if consultation is paid
- `getTotalPaid()`: Get total amount paid

## Services

### PaymentService (`app/Services/PaymentService.php`)

Core payment processing service with methods:
- `createPaymentIntent()`: Create a Stripe payment intent
- `confirmPaymentIntent()`: Confirm a payment
- `getPaymentIntent()`: Retrieve intent from Stripe
- `updatePaymentFromIntent()`: Update payment from Stripe data
- `refundPayment()`: Process refund
- `cancelPayment()`: Cancel pending payment
- `verifyWebhookSignature()`: Verify Stripe webhook
- `handlePaymentIntentSucceeded()`: Handle success webhook
- `handlePaymentIntentFailed()`: Handle failure webhook
- `getPaymentHistory()`: Retrieve customer payment history
- `getPaymentStats()`: Get payment statistics

## Frontend Integration

### Consultation Payment Form View
Located at `resources/views/payments/consultation-form.blade.php`

The view includes:
- Stripe Elements card form
- Real-time validation
- Payment processing with error handling
- Automatic success/failure page redirect

### Example Usage

```html
<!-- Link to payment form -->
<a href="/payments/consultation/form?amount=99.99&email=customer@example.com&name=John%20Doe">
    Pay for Consultation
</a>
```

### JavaScript Integration

```javascript
// Create payment method and confirm
const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
    payment_method: {
        card: cardElement,
        billing_details: {
            email: customerEmail,
            name: customerName
        }
    }
});
```

## Webhook Setup

### Configure Webhook in Stripe Dashboard

1. Go to [Stripe Dashboard](https://dashboard.stripe.com) → Developers → Webhooks
2. Click "Add endpoint"
3. Endpoint URL: `https://yourdomain.com/webhooks/stripe`
4. Events to send:
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
   - `payment_intent.canceled`
   - `charge.refunded`
   - `charge.dispute.created`

5. Copy the Webhook Signing Secret to your `.env` as `STRIPE_WEBHOOK_SECRET`

### Handled Webhook Events

- **payment_intent.succeeded**: Mark payment as succeeded
- **payment_intent.payment_failed**: Mark payment as failed
- **payment_intent.canceled**: Mark payment as canceled
- **charge.refunded**: Process refund
- **charge.dispute.created**: Log dispute for admin review

## Error Handling

The payment system handles various error scenarios:

- Invalid card information
- Declined cards
- Network errors
- Webhook signature verification failures
- Invalid payment amounts
- Missing required parameters

All errors are logged to `storage/logs/laravel.log` and appropriate error messages are returned to the client.

## Testing

### Using Stripe Test Cards

For testing in sandbox mode, use these test cards:

| Card Number | Status |
|------------|--------|
| 4242 4242 4242 4242 | Succeeds |
| 4000 0000 0000 0002 | Decline |
| 4000 0025 0000 3155 | 3D Secure Auth Required |

All test cards use any future expiration date and any 3-digit CVC.

### Example Test Payment

```bash
curl -X POST http://localhost:8000/payments/create-intent \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your_csrf_token" \
  -d '{
    "amount": 99.99,
    "email": "test@example.com",
    "customer_name": "John Doe",
    "payment_type": "consultation",
    "description": "Test consultation payment"
  }'
```

## Security Considerations

1. **CSRF Protection**: All payment endpoints are protected with CSRF tokens
2. **Data Encryption**: Customer email and name are encrypted in the database
3. **Webhook Verification**: All webhooks are verified against Stripe signature
4. **SSL/TLS**: Always use HTTPS in production
5. **PCI Compliance**: Card data is never stored; Stripe handles all sensitive data
6. **IP Logging**: Customer IP addresses are logged for security auditing

## Admin Dashboard Integration

The payment system integrates with the admin dashboard for:
- Viewing payment statistics
- Monitoring failed payments
- Processing refunds
- Viewing payment history
- Exporting payment reports

## Troubleshooting

### Common Issues

**Issue: "Invalid API Key"**
- Ensure `STRIPE_SECRET_KEY` is correctly set in `.env`
- Check that keys haven't been revoked in Stripe Dashboard

**Issue: "Webhook signature verification failed"**
- Verify `STRIPE_WEBHOOK_SECRET` matches the endpoint secret in Stripe Dashboard
- Ensure the webhook is configured for the correct events

**Issue: "Connection timeout"**
- Check internet connectivity
- Verify Stripe API is accessible
- Check firewall rules

**Issue: "Payment processing hangs"**
- Check that PaymentService is properly instantiated
- Review logs for specific errors
- Ensure Stripe keys have appropriate permissions

## Logs

Payment operations are logged to:
- `storage/logs/laravel.log`

Log levels:
- **INFO**: Successful operations
- **WARNING**: Failed payments, dispute notifications
- **ERROR**: System errors, API failures

## Future Enhancements

Consider adding:
- Subscription/recurring payments
- Multiple payment gateway support (PayPal, etc.)
- Advanced fraud detection
- Payment splitting/split payments
- Invoice generation and emailing
- Multi-currency support for different regions
- Custom payment receipts/invoices
- Payment analytics dashboard
- SCA/3D Secure handling for EU

## Support

For issues with the payment system:
1. Check logs in `storage/logs/laravel.log`
2. Verify Stripe configuration and keys
3. Review this documentation
4. Contact Stripe support at support@stripe.com for API-related issues

## References

- [Stripe Documentation](https://stripe.com/docs)
- [Stripe PHP Library](https://github.com/stripe/stripe-php)
- [Laravel Payment Processing](https://laravel.com/docs)
