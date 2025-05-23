<?php

namespace AmaizingCompany\CertifactionClient\Jobs;

use AmaizingCompany\CertifactionClient\Api\Requests\PrepareDocumentRequest;
use AmaizingCompany\CertifactionClient\Contracts\Document;
use AmaizingCompany\CertifactionClient\Contracts\Events\DocumentPreparationFailed;
use AmaizingCompany\CertifactionClient\Contracts\Events\DocumentPrepared;
use AmaizingCompany\CertifactionClient\Contracts\Signable;
use AmaizingCompany\CertifactionClient\Contracts\SignatureTransaction;
use AmaizingCompany\CertifactionClient\Enums\DocumentPrepareScope;
use AmaizingCompany\CertifactionClient\Enums\DocumentStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class ProcessPrepareDocumentRequest implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public DocumentPrepareScope $signatureType,
        public Signable $signable,
        public SignatureTransaction $transaction
    ) {}

    public function handle()
    {
        $request = new PrepareDocumentRequest($this->signable->getFileContents(), $this->signatureType);

        $request
            ->pdfA($this->signable->isPdfA())
            ->digitalTwin($this->signable->hasDigitalTwin())
            ->additionalPageForSignature($this->signable->hasAdditionalPage())
            ->upload();

        if ($this->signable->hasDigitalTwin()) {
            $request
                ->digitalTwinQrPosition($this->signable->qrCodePositionX(), $this->signable->qrCodePositionY())
                ->digitalTwinQrPageNumber($this->signable->qrCodePageNumber())
                ->digitalTwinQrHeight($this->signable->qrCodeHeight());
        }

        try {
            $response = $request->send()->throw();
        } catch (\Throwable $e) {
            Event::dispatch(app(DocumentPreparationFailed::class, [
                'request' => $request,
                'signable' => $this->signable,
                'exception' => $e,
            ]));

            Log::warning($e->getMessage(), ['signable_id' => $this->signable->getKey()]);

            return;
        }

        if (! $response->successful()) {
            Event::dispatch(app(DocumentPreparationFailed::class, [
                'request' => $request,
                'signable' => $this->signable,
            ]));

            Log::warning('Document preparation failed.', ['signable_id' => $this->signable->getKey()]);

            return;
        }

        /**
         * @var Document $document
         */
        $document = $this->signable->certifactionDocuments()->create([
            'external_id' => $response->getFileId(),
            'location' => $response->getFileLocation(),
            'status' => DocumentStatus::PREPARED,
            'scope' => $request->getScope(),
        ]);

        $this->transaction->documents()->attach($document);

        Event::dispatch(app(DocumentPrepared::class, ['document' => $document]));
    }
}
