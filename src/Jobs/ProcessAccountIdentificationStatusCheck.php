<?php

namespace AmaizingCompany\CertifactionClient\Jobs;

use AmaizingCompany\CertifactionClient\Api\Requests\CheckIdentificationStatusRequest;
use AmaizingCompany\CertifactionClient\Contracts\IdentityTransaction;
use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
use AmaizingCompany\CertifactionClient\Events\IdentificationRequestFinished;
use AmaizingCompany\CertifactionClient\Events\IdentificationStatusCheckFinished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessAccountIdentificationStatusCheck implements ShouldQueue
{
    use Queueable;

    public function __construct(protected IdentityTransaction $identityTransaction) {}

    public function handle(): void
    {
        try {
            $response = CheckIdentificationStatusRequest::make($this->identityTransaction->external_id)->send()->throw();
        } catch (\Throwable $e) {
            Log::warning($e->getMessage(), $e->getCode());

            return;
        }

        if ($response->successful()) {
            $status = $response->getIdentificationStatus();

            $this->identityTransaction->finish($status);

            if ($status === IdentificationStatus::VERIFIED) {
                $this->identityTransaction->account->markAsIdentified();
            }

            IdentificationRequestFinished::dispatch($this->identityTransaction, $status);
        }

        IdentificationStatusCheckFinished::dispatch($this->identityTransaction);
    }
}
