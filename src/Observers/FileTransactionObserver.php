<?php

namespace AmaizingCompany\CertifactionClient\Observers;

use AmaizingCompany\CertifactionClient\Contracts\FileTransaction;
use AmaizingCompany\CertifactionClient\Enums\FileTransactionStatus;
use AmaizingCompany\CertifactionClient\Events\FileTransactionFailed;
use AmaizingCompany\CertifactionClient\Events\FileTransactionFinished;
use AmaizingCompany\CertifactionClient\Events\FileTransactionStarted;

class FileTransactionObserver
{
    public function updated(FileTransaction $transaction): void
    {
        if ($transaction->status !== $transaction->getOriginal('status')) {
            switch ($transaction->status) {
                case FileTransactionStatus::PENDING:
                    FileTransactionStarted::dispatch($transaction);
                    break;

                case FileTransactionStatus::FAILURE:
                    FileTransactionFailed::dispatch($transaction);
                    break;

                case FileTransactionStatus::SUCCESS:
                    FileTransactionFinished::dispatch($transaction);
                    break;

                default:
                    break;
            }
        }
    }
}
