<?php

namespace AmaizingCompany\CertifactionClient\Observers;

use AmaizingCompany\CertifactionClient\Contracts\IdentityTransaction;
use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
use AmaizingCompany\CertifactionClient\Exceptions\TooManyIdentificationRequestsPerAccountException;

class IdentityTransactionObserver
{
    /**
     * @throws TooManyIdentificationRequestsPerAccountException
     */
    public function creating(IdentityTransaction $transaction): void
    {
        if (app(IdentityTransaction::class)::query()
            ->where('account_id', $transaction->account_id)
            ->whereIn('status', [IdentificationStatus::PENDING, IdentificationStatus::INTENT])
            ->exists()
        ) {
            throw new TooManyIdentificationRequestsPerAccountException;
        }
    }
}
