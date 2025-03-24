<?php

namespace AmaizingCompany\CertifactionClient\Contracts;
use AmaizingCompany\CertifactionClient\Enums\AccountStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property CertifactionUser $user
 * @property string $user_type
 * @property string $user_id
 * @property int $external_id
 * @property string $external_uid
 * @property string $role_id
 * @property AccountStatus $status
 * @property bool $admin
 * @property bool $identified
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
interface Account
{
    /**
     * Get the pending identity transaction if exists.
     *
     * @return IdentityTransaction|null
     */
    public function getPendingIdentityTransaction(): IdentityTransaction|null;

    /**
     * Check if the account has a pending request for identification.
     *
     * @return bool
     */
    public function hasPendingIdentificationRequest(): bool;

    /**
     * Identity transaction model relationship.
     *
     * @return HasMany
     */
    public function identityTransactions(): HasMany;

    /**
     * Check if the account status is invited.
     *
     * @return bool
     */
    public function isInvited(): bool;

    /**
     * Check if the account status is joined.
     *
     * @return bool
     */
    public function isJoined(): bool;

    /**
     * Change account status to identified.
     *
     * @return bool
     */
    public function markAsIdentified(): bool;

    /**
     * User model relationship.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo;
}
