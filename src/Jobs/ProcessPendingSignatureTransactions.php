<?php

namespace AmaizingCompany\CertifactionClient\Jobs;

use AmaizingCompany\CertifactionClient\Contracts\SignatureTransaction;
use AmaizingCompany\CertifactionClient\Enums\SignatureTransactionStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;

class ProcessPendingSignatureTransactions implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        app(SignatureTransaction::class)::query()
            ->where('status', SignatureTransactionStatus::PENDING)
            ->where('requested_at', '>', Carbon::now()->addMinutes(10))
            ->each(function (SignatureTransaction $transaction) {
                ProcessWebhook::dispatch($transaction);
            });
    }
}
