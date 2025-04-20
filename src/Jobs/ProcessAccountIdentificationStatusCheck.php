<?php

namespace AmaizingCompany\CertifactionClient\Jobs;

use AmaizingCompany\CertifactionClient\Api\Requests\CheckIdentificationStatusRequest;
use AmaizingCompany\CertifactionClient\Contracts\Events\IdentificationRequestFinished;
use AmaizingCompany\CertifactionClient\Contracts\Events\IdentificationStatusCheckFinished;
use AmaizingCompany\CertifactionClient\Contracts\IdentityTransaction;
use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Event;
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
            Log::warning($e->getMessage(), ['identity_transaction_id' => $this->identityTransaction->getKey()]);

            return;
        }

        if ($response->successful()) {
            $status = $response->getIdentificationStatus();

            $this->identityTransaction->finish($status);

            if ($status === IdentificationStatus::VERIFIED) {
                $this->identityTransaction->account->markAsIdentified();
            }

            Event::dispatch(app(IdentificationRequestFinished::class, [
                'identityTransaction' => $this->identityTransaction,
                'status' => $status
            ]));
        }

        Event::dispatch(app(IdentificationStatusCheckFinished::class, [
            'identityTransaction' => $this->identityTransaction,
        ]));
    }
}
