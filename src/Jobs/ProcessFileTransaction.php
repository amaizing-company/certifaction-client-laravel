<?php

namespace AmaizingCompany\CertifactionClient\Jobs;

use AmaizingCompany\CertifactionClient\Api\Requests\DownloadDocumentRequest;
use AmaizingCompany\CertifactionClient\Contracts\FileTransaction;
use AmaizingCompany\CertifactionClient\Enums\FileTransactionStatus;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessFileTransaction implements ShouldBeUniqueUntilProcessing, ShouldQueue
{
    use Queueable;

    public function __construct(public FileTransaction $transaction) {}

    public function handle(): void
    {
        if (! in_array($this->transaction->status, [FileTransactionStatus::PENDING, FileTransactionStatus::INTENT])) {
            Log::warning('File transaction already processed', ['file_transaction_id' => $this->transaction->id]);

            return;
        }

        $this->transaction->markPending();

        try {
            $response = DownloadDocumentRequest::make($this->transaction->file_url)->send()->throw();
        } catch (\Throwable $e) {
            Log::warning($e->getMessage(), ['file_transaction_id' => $this->transaction->id]);

            $this->transaction->markFailure($e->getMessage());

            return;
        }

        if ($response->successful()) {
            $status = $response->saveFile(
                $this->transaction->document->getStoragePath(),
                $this->transaction->document->getStorageDisk()
            );

            if ($status) {
                $this->transaction->markSuccess();
            } else {
                Log::error('Error while storing document', ['file_transaction_id' => $this->transaction->id]);

                $this->transaction->markFailure('Error while storing document.');
            }

            return;
        }

        if ($response->failed()) {
            Log::warning('Error while requesting a download for document', ['file_transaction_id' => $this->transaction->id]);

            $this->transaction->markFailure('Error while requesting a download for document.');
        }
    }
}
