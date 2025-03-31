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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @phpstan-ignore trait.unused
 */
trait HasCertifactionAccount
{
    public function certifactionAccount(): HasOne
    {
        return $this->hasOne(app(Account::class));
    }

    public function inviteToCertifaction(?string $roleId = null): static
    {
        ProcessUserInvitation::dispatch($this, $roleId);

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

    public function signatureTransactions(): HasMany
    {
        return $this->hasMany(app(SignatureTransaction::class));
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
