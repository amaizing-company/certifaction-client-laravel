<?php

namespace AmaizingCompany\CertifactionClient\Concerns;

use AmaizingCompany\CertifactionClient\Api\DataObjects\Signer;
use AmaizingCompany\CertifactionClient\Contracts\Account;
use AmaizingCompany\CertifactionClient\Contracts\SignatureTransaction;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use AmaizingCompany\CertifactionClient\Enums\SignatureTransactionStatus;
use AmaizingCompany\CertifactionClient\Enums\SignatureType;
use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;
use AmaizingCompany\CertifactionClient\Jobs\ProcessUserInvitation;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @phpstan-ignore trait.unused
 */
trait HasCertifactionAccount
{
    public function certifactionAccount(): MorphOne
    {
        return $this->morphOne(app(Account::class), 'user');
    }

    public function inviteToCertifaction(?string $roleId = null, bool $sync = false): static
    {
        if ($sync) {
            ProcessUserInvitation::dispatchSync($this, $roleId);
        } else {
            ProcessUserInvitation::dispatch($this, $roleId);
        }

        return $this;
    }

    public function getCertifactionSignerObject(): Signer
    {
        return new Signer($this->getEmail())
            ->firstName($this->getFirstName())
            ->lastName($this->getLastName())
            ->mobilePhone($this->getMobilePhone())
            ->birthdate($this->getBirthdate());
    }

    public function signatureTransactions(): MorphMany
    {
        return $this->morphMany(app(SignatureTransaction::class));
    }

    public function createSignatureTransaction(SignatureType $type, ?Jurisdiction $jurisdiction = null): SignatureTransaction
    {
        return $this->signatureTransactions()->create([
            'signature_type' => $type,
            'jurisdiction' => $jurisdiction ?? CertifactionClient::getConfig('jurisdiction'),
            'status' => SignatureTransactionStatus::INTENDED,
        ]);
    }
}
