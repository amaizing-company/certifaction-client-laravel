<?php

namespace AmaizingCompany\CertifactionClient\Jobs;

use AmaizingCompany\CertifactionClient\Api\Requests\StartAccountIdentificationRequest;
use AmaizingCompany\CertifactionClient\Contracts\IdentityTransaction;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use AmaizingCompany\CertifactionClient\Events\IdentitifcationRequestStarted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessAccountIdentificationRequest implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected IdentityTransaction $identityTransaction,
        protected ?Jurisdiction $jurisdiction = null
    ) {
        $this->identityTransaction->loadMissing('account.user');

        if (empty($this->jurisdiction)) {
            $this->jurisdiction = config('certifaction-client-laravel.defaults.jurisdiction');
        }
    }

    public function handle(): void
    {
        $user = $this->identityTransaction->account->user;

        $this->identityTransaction->updateLastRequest();

        try {
            $response = StartAccountIdentificationRequest::make(
                $user->getEmail(),
                $user->getFirstName(),
                $user->getLastName(),
                $user->getMobilePhone(),
                $this->identityTransaction->identification_method,
                $this->jurisdiction
            )->send()->throw();
        } catch (Throwable $e) {
            Log::warning($e->getMessage(), ['identity_transaction_id' => $this->identityTransaction->getKey()]);

            return;
        }

        if ($response->successful()) {
            $this->identityTransaction->pending($response->getIdentityId(), $response->getIdentificationUri());

            IdentitifcationRequestStarted::dispatch($this->identityTransaction);
        }
    }
}
