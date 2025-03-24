<?php

namespace AmaizingCompany\CertifactionClient\Contracts;

use AmaizingCompany\CertifactionClient\Enums\DocumentType;
use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
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
 */
interface IdentityTransaction
{
    /**
     * Account model relationship.
     *
     * @return BelongsTo
     */
    public function account(): BelongsTo;

    /**
     * Finish the identity transaction.
     *
     * @param IdentificationStatus $status
     * @param Carbon|null $lastCheck
     * @return bool
     */
    public function finish(IdentificationStatus $status, ?Carbon $lastCheck = null): bool;

    /**
     * Check if the current status is failed.
     *
     * @return bool
     */
    public function isFailed(): bool;

    /**
     * Check if the current status is finished.
     *
     * @return bool
     */
    public function isFinished(): bool;

    /**
     * Check if the current status is pending.
     *
     * @return bool
     */
    public function isPending(): bool;

    /**
     * Check if the current status is verified.
     *
     * @return bool
     */
    public function isVerified(): bool;

    /**
     * Set the identity transaction status to pending.
     *
     * @param string $identificationId
     * @param string $identificationUrl
     * @return bool
     */
    public function pending(string $identificationId, string $identificationUrl): bool;

    /**
     * Update the timestamp for last identity status checkup.
     *
     * @param Carbon|null $lastCheck
     * @return bool
     */
    public function updateLastCheck(?Carbon $lastCheck = null): bool;

    /**
     * Update timestamp for last request.
     *
     * @param Carbon|null $lastRequest
     * @return bool
     */
    public function updateLastRequest(?Carbon $lastRequest = null): bool;
}
