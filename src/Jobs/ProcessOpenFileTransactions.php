<?php

namespace AmaizingCompany\CertifactionClient\Jobs;

use AmaizingCompany\CertifactionClient\Contracts\FileTransaction;
use AmaizingCompany\CertifactionClient\Enums\FileTransactionStatus;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessOpenFileTransactions implements ShouldBeUniqueUntilProcessing, ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        app(FileTransaction::class)->query()
            ->where('status', FileTransactionStatus::INTENT)
            ->each(function (FileTransaction $transaction) {
                ProcessFileTransaction::dispatch($transaction);
            });
    }
}
