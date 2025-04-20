<?php

namespace AmaizingCompany\CertifactionClient\Observers;

use AmaizingCompany\CertifactionClient\Contracts\Events\FileTransactionFailed;
use AmaizingCompany\CertifactionClient\Contracts\Events\FileTransactionFinished;
use AmaizingCompany\CertifactionClient\Contracts\Events\FileTransactionStarted;
use AmaizingCompany\CertifactionClient\Contracts\FileTransaction;
use AmaizingCompany\CertifactionClient\Enums\FileTransactionStatus;
use Illuminate\Support\Facades\Event;

class FileTransactionObserver
{
    public function updated(FileTransaction $transaction): void
    {
        if ($transaction->status !== $transaction->getOriginal('status')) {
            switch ($transaction->status) {
                case FileTransactionStatus::PENDING:
                    Event::dispatch(app(FileTransactionStarted::class, ['transaction' => $transaction]));
                    break;

                case FileTransactionStatus::FAILURE:
                    Event::dispatch(app(FileTransactionFailed::class, ['transaction' => $transaction]));
                    break;

                case FileTransactionStatus::SUCCESS:
                    Event::dispatch(app(FileTransactionFinished::class, ['transaction' => $transaction]));
                    break;

                default:
                    break;
            }
        }
    }
}
