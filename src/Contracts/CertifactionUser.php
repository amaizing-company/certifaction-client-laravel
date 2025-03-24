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
     *
     * @return string|Carbon
     */
    public function getBirthdate(): string|Carbon;

    /**
     * Email of the user.
     *
     * @return string
     */
    public function getEmail(): string;

    /**
     * First name of the user.
     *
     * @return string
     */
    public function getFirstName(): string;

    /**
     * Last name of the user.
     *
     * @return string
     */
    public function getLastName(): string;

    /**
     * A valid mobile phone number of the user.
     *
     * @return string
     */
    public function getMobilePhone(): string;

    /**
     * Start an invitation process for the user to certifaction.
     *
     * @param string $roleId
     * @return $this
     */
    public function inviteToCertifaction(string $roleId): static;

    /**
     * The certifaction user account model.
     *
     * @return HasOne
     */
    public function certifactionAccount(): HasOne;

    /**
     * Related signature transactions.
     *
     * @return HasMany
     */
    public function signatureTransactions(): HasMany;

    /**
     * Transformed user data to a certifaction signer object which is used to start signature requests.
     *
     * @return Signer
     */
    public function getCertifactionSignerObject(): Signer;
}
