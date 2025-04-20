<?php

namespace AmaizingCompany\CertifactionClient\Jobs;

use AmaizingCompany\CertifactionClient\Api\Requests\CheckSignatureStatusRequest;
use AmaizingCompany\CertifactionClient\Api\Responses\CheckSignatureStatusResponse;
use AmaizingCompany\CertifactionClient\Contracts\Document;
use AmaizingCompany\CertifactionClient\Contracts\Events\SignatureRequestFailed;
use AmaizingCompany\CertifactionClient\Contracts\Events\SignatureRequestFinished;
use AmaizingCompany\CertifactionClient\Contracts\FileTransaction;
use AmaizingCompany\CertifactionClient\Contracts\SignatureTransaction;
use AmaizingCompany\CertifactionClient\Enums\DocumentStatus;
use AmaizingCompany\CertifactionClient\Enums\FileTransactionStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class ProcessWebhook implements ShouldQueue
{
    use Queueable;

    public function __construct(public SignatureTransaction $transaction) {}

    public function handle(): void
    {
        try {
            $response = CheckSignatureStatusRequest::make($this->transaction->request_url)->send()->throw();
        } catch (\Throwable $exception) {
            Log::warning($exception->getMessage(), ['transaction_id' => $this->transaction->id]);

            return;
        }

        if ($response->hasAllItemsUnsigned()) {
            return;
        }

        if ($response->isCancelled()) {
            $this->transaction->markFailed('cancelled');

            Event::dispatch(app(SignatureRequestFailed::class, ['transaction' => $this->transaction]));

            return;
        }

        $this->handleSignedItems($response);
        $this->handleUnsignedItems($response);

        $this->transaction->markFinished();

        Event::dispatch(app(SignatureRequestFinished::class, ['transaction' => $this->transaction]));
    }

    protected function handleSignedItems(CheckSignatureStatusResponse $response): void
    {
        $signedItems = $response->getSignedEnvelopeItems();
        $documents = $this->transaction->documents;

        foreach ($signedItems as $signedItem) {
            $documentId = $documents->find('external_id', $signedItem->getFileId())->get('id');
            $signedIds[] = $documentId;

            app(FileTransaction::class)->query()
                ->create([
                    'document_id' => $documentId,
                    'status' => FileTransactionStatus::INTENT,
                    'file_url' => $signedItem->getFileUrl(),
                ]);
        }

        if (! empty($signedIds)) {
            app(Document::class)->query()
                ->whereIn('id', $signedIds)
                ->update([
                    'status' => DocumentStatus::SIGNED,
                ]);
        }
    }

    protected function handleUnsignedItems(CheckSignatureStatusResponse $response): void
    {
        if ($response->hasUnsignedItems()) {
            foreach ($response->getUnsignedEnvelopeItems() as $item) {
                $unsignedItemIds[] = $item->getFileId();
            }
        }

        if (! empty($unsignedItemIds)) {
            app(Document::class)->query()
                ->whereIn('id', $unsignedItemIds)
                ->update([
                    'status' => DocumentStatus::SIGNATURE_FAILED,
                    'failure_reason' => 'unsigned',
                ]);
        }
    }
}
