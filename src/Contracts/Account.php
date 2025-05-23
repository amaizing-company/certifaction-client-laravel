<?php

namespace AmaizingCompany\CertifactionClient\Contracts;

use AmaizingCompany\CertifactionClient\Enums\AccountStatus;
use AmaizingCompany\CertifactionClient\Enums\DocumentType;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property CertifactionUser $user
 * @property string $user_type
 * @property string $user_id
 * @property string $invite_email
 * @property int $external_id
 * @property string $external_uid
 * @property string $role_id
 * @property AccountStatus $status
 * @property bool $admin
 * @property bool $identified
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @mixin Model
 */
interface Account
{
    /**
     * Get the pending identity transaction if exists.
     */
    public function getPendingIdentityTransaction(): ?IdentityTransaction;

    /**
     * Check if the account has a pending request for identification.
     */
    public function hasPendingIdentificationRequest(): bool;

    /**
     * Identity transaction model relationship.
     */
    public function identityTransactions(): HasMany;

    /**
     * Check if the account status is invited.
     */
    public function isInvited(): bool;

    /**
     * Check if the account status is joined.
     */
    public function isJoined(): bool;

    /**
     * Change account status to identified.
     */
    public function markAsIdentified(): bool;

    /**
     * Request an account deletion.
     */
    public function requestDeletion(bool $sync = false): void;

    /**
     * Request an account identification.
     */
    public function requestIdentification(DocumentType $documentType, ?Jurisdiction $jurisdiction = null, bool $sync = false): bool;

    /**
     * User model relationship.
     */
    public function user(): MorphTo;
}
