<?php

namespace AmaizingCompany\CertifactionClient\Jobs;

use AmaizingCompany\CertifactionClient\Contracts\IdentityTransaction;
use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessIntentedIdentityTransactions implements ShouldQueue
{
    public function handle()
    {
        app(IdentityTransaction::class)::query()
            ->where('status', IdentificationStatus::INTENT)
            ->each(function (IdentityTransaction $transaction) {
                ProcessAccountIdentificationRequest::dispatch($transaction);
            });
    }
}
