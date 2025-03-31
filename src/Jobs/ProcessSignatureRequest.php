<?php

namespace AmaizingCompany\CertifactionClient\Jobs;

use AmaizingCompany\CertifactionClient\Api\DataObjects\DocumentItem;
use AmaizingCompany\CertifactionClient\Api\Requests\SignatureRequest;
use AmaizingCompany\CertifactionClient\Contracts\Signable;
use AmaizingCompany\CertifactionClient\Contracts\SignatureTransaction;
use AmaizingCompany\CertifactionClient\Events\SignatureRequestFailed;
use AmaizingCompany\CertifactionClient\Events\SignatureRequestStarted;
use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessSignatureRequest implements ShouldQueue
{
    use Queueable;

    public function __construct(public SignatureTransaction $transaction, public ?bool $notifySigner = null)
    {
        if (empty($this->notifySigner)) {
            $this->notifySigner = CertifactionClient::getConfig('notify_signer');
        }
    }

    public function handle()
    {
        if ($this->transaction->documents->isEmpty()) {
            Log::warning('There must be at least one document to be processed.', ['transaction_id' => $this->transaction->id]);

            SignatureRequestFailed::dispatch($this->transaction);

            return;
        }

        /**
         * @var Signable $signable
         */
        $signable = $this->transaction->documents->first()->signable;

        $request = SignatureRequest::make()
            ->for($this->transaction->signer->getCertifactionSignerObject())
            ->jurisdiction($this->transaction->jurisdiction)
            ->signatureType($this->transaction->signature_type)
            ->digitalTwin($signable->hasDigitalTwin())
            ->pdfA($signable->isPdfA())
            ->notifySigner($this->notifySigner)
            ->webhookUrl($signable->getWebhookUrl())
            ->additionalPageForSignature($signable->hasAdditionalPage())
            ->transactionId($this->transaction->id);

        if ($signable->hasDigitalTwin()) {
            $request
                ->digitalTwinQrPosition($signable->qrCodePositionX(), $signable->qrCodePositionY())
                ->digitalTwinQrPageNumber($signable->qrCodePageNumber())
                ->digitalTwinQrHeight($signable->qrCodeHeight());
        }

        if (! $signable->hasAdditionalPage()) {
            $request
                ->signaturePosition($signable->signaturePositionX(), $signable->signaturePositionY())
                ->signatureHeight($signable->signatureHeight())
                ->signaturePageNumber($signable->signaturePageNumber());
        }

        if ($signable->withPassword()) {
            $request->withPassword($signable->getEncryptionKey());
        }

        $this->addDocuments($request);

        try {
            $response = $request->send()->throw();
        } catch (\Throwable $e) {
            Log::warning($e->getMessage(), ['signature_transaction_id' => $this->transaction->getKey()]);

            SignatureRequestFailed::dispatch($this->transaction);

            return;
        }

        if ($response->successful()) {
            $this->transaction->markPending($response->getRequestUrl());

            SignatureRequestStarted::dispatch($this->transaction);
        }
    }

    protected function addDocuments(SignatureRequest &$request): void
    {
        foreach ($this->transaction->documents as $document) {
            if (! $document->isPrepared()) {
                Log::warning('The document is not prepared.', ['document_id' => $document->id, 'transaction_id' => $this->transaction->id]);

                continue;
            }

            $item = new DocumentItem($document->location, $document->signable->getDocumentName());
            $request->addDocument($item);
        }
    }
}
