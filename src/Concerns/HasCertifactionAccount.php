<?php

namespace AmaizingCompany\CertifactionClient\Concerns;

use AmaizingCompany\CertifactionClient\Api\DataObjects\Signer;
use AmaizingCompany\CertifactionClient\Contracts\Account;
use AmaizingCompany\CertifactionClient\Jobs\ProcessUserInvitation;
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

    public function inviteToCertifaction(string $roleId): static
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
}
