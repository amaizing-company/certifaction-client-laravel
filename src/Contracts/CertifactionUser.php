<?php

namespace AmaizingCompany\CertifactionClient\Contracts;

use AmaizingCompany\CertifactionClient\Api\DataObjects\Signer;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

interface CertifactionUser
{
    /**
     * Birthdate of the user.
     */
    public function getBirthdate(): string|Carbon;

    /**
     * Email of the user.
     */
    public function getEmail(): string;

    /**
     * First name of the user.
     */
    public function getFirstName(): string;

    /**
     * Last name of the user.
     */
    public function getLastName(): string;

    /**
     * A valid mobile phone number of the user.
     */
    public function getMobilePhone(): string;

    /**
     * Start an invitation process for the user to certifaction.
     *
     * @return $this
     */
    public function inviteToCertifaction(string $roleId): static;

    /**
     * The certifaction user account model.
     */
    public function certifactionAccount(): HasOne;

    /**
     * Related signature transactions.
     */
    public function signatureTransactions(): HasMany;

    /**
     * Transformed user data to a certifaction signer object which is used to start signature requests.
     */
    public function getCertifactionSignerObject(): Signer;
}
