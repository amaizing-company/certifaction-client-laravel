<?php

namespace AmaizingCompany\CertifactionClient\Jobs;

use AmaizingCompany\CertifactionClient\Contracts\IdentityTransaction;
use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessPendingIdentityTransactions implements ShouldQueue
{
    use Queueable;

    public function handle()
    {
        app(IdentityTransaction::class)::query()
            ->where('status', IdentificationStatus::PENDING)
            ->each(function (IdentityTransaction $transaction) {
                ProcessAccountIdentificationStatusCheck::dispatch($transaction->account);
            });
    }
}
