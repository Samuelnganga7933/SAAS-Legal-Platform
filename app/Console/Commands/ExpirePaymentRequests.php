<?php

namespace App\Console\Commands;

use App\Models\PaymentRequest;
use Illuminate\Console\Command;

class ExpirePaymentRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:expire-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire payment requests that have passed their expiration date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredCount = PaymentRequest::where('expires_at', '<', now())
            ->whereIn('status', [PaymentRequest::STATUS_SENT, PaymentRequest::STATUS_VIEWED])
            ->update(['status' => PaymentRequest::STATUS_EXPIRED]);

        $this->info("Expired {$expiredCount} payment requests.");

        return Command::SUCCESS;
    }
}
