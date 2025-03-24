<?php

namespace AmaizingCompany\CertifactionClient\Jobs;

use AmaizingCompany\CertifactionClient\Api\Requests\PrepareDocumentRequest;
use AmaizingCompany\CertifactionClient\Contracts\Signable;
use AmaizingCompany\CertifactionClient\Enums\DocumentStatus;
use AmaizingCompany\CertifactionClient\Events\DocumentPreparationFailed;
use AmaizingCompany\CertifactionClient\Events\DocumentPrepared;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessPrepareDocumentRequest implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public PrepareDocumentRequest $request,
        public Signable $signable,
    )
    {}

    public function handle()
    {
        try {
            $response = $this->request->upload()->send()->throw();
        } catch (\Throwable $e) {
            DocumentPreparationFailed::dispatch($this->request, $e);

            Log::warning($e->getMessage(), $e->getCode());

            return;
        }

        if (!$response->successful()) {
            return;
        }

        $document = $this->signable->certifactionDocuments()->create([
            'external_id' => $response->getFileId(),
            'location' => $response->getFileLocation(),
            'status' => DocumentStatus::PREPARED,
            'scope' => $this->request->getScope(),
        ]);

        DocumentPrepared::dispatch($document);
    }
}
