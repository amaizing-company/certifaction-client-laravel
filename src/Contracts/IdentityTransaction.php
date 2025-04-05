<?php

namespace AmaizingCompany\CertifactionClient\Contracts;

use AmaizingCompany\CertifactionClient\Enums\DocumentType;
use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property Account $account
 * @property string $account_id
 * @property string $external_id
 * @property IdentificationStatus $status
 * @property DocumentType $identification_method
 * @property string|null $identification_uri
 * @property Carbon|null $requested_at
 * @property Carbon|null $last_check_at
 * @property Carbon|null $finished_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @mixin Model
 */
interface IdentityTransaction
{
    /**
     * Account model relationship.
     */
    public function account(): BelongsTo;

    /**
     * Finish the identity transaction.
     */
    public function finish(IdentificationStatus $status, ?Carbon $lastCheck = null): bool;

    /**
     * Check if the current status is failed.
     */
    public function isFailed(): bool;

    /**
     * Check if the current status is finished.
     */
    public function isFinished(): bool;

    /**
     * Check if the current status is intent.
     */
    public function isIntent(): bool;

    /**
     * Check if the current status is pending.
     */
    public function isPending(): bool;

    /**
     * Check if the current status is verified.
     */
    public function isVerified(): bool;

    /**
     * Set the identity transaction status to pending.
     */
    public function pending(string $identificationId, string $identificationUrl): bool;

    /**
     * Request an account identity status check.
     *
     * @param bool $sync
     * @return bool
     */
    public function requestStatusCheck(bool $sync = false): bool;

    /**
     * Update the timestamp for last identity status checkup.
     */
    public function updateLastCheck(?Carbon $lastCheck = null): bool;

    /**
     * Update timestamp for last request.
     */
    public function updateLastRequest(?Carbon $lastRequest = null): bool;
}
