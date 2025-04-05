<?php

namespace AmaizingCompany\CertifactionClient\Contracts;

use AmaizingCompany\CertifactionClient\Api\DataObjects\Signer;
use AmaizingCompany\CertifactionClient\Enums\SignatureType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @mixin Model
 */
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
    public function inviteToCertifaction(?string $roleId = null, bool $sync = false): static;

    /**
     * The certifaction user account model.
     */
    public function certifactionAccount(): HasOne;

    /**
     * Create a new signature transaction.
     */
    public function createSignatureTransaction(SignatureType $type): SignatureTransaction;

    /**
     * Related signature transactions.
     */
    public function signatureTransactions(): HasMany;

    /**
     * Transformed user data to a certifaction signer object which is used to start signature requests.
     */
    public function getCertifactionSignerObject(): Signer;
}
