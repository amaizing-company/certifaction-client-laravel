<?php

namespace AmaizingCompany\CertifactionClient\Contracts;

use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use AmaizingCompany\CertifactionClient\Enums\SignatureTransactionStatus;
use AmaizingCompany\CertifactionClient\Enums\SignatureType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $signer_type
 * @property string $signer_id
 * @property CertifactionUser $signer
 * @property Collection<Document> $documents
 * @property SignatureType $signature_type
 * @property Jurisdiction $jurisdiction
 * @property SignatureTransactionStatus $status
 * @property string|null $request_url
 * @property string|null $failure_reason
 * @property Carbon|null $requested_at
 * @property Carbon|null $finished_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
*/
interface SignatureTransaction
{
    /**
     * Document model relationship.
     *
     * @return BelongsToMany
     */
    public function documents(): BelongsToMany;

    /**
     * Get the webhook url that will be called if an signature request was finished.
     *
     * @return string
     */
    public function getWebhookUrl(): string;

    /**
     * Mark the current transaction as pending.
     *
     * @param string $requestUrl
     * @param Carbon|null $requestedAt
     * @return bool
     */
    public function markPending(string $requestUrl, ?Carbon $requestedAt = null): bool;

    /**
     * Mark the current transaction as failed.
     *
     * @param string $failureReason
     * @param Carbon|null $finishedAt
     * @return bool
     */
    public function markFailed(string $failureReason, ?Carbon $finishedAt = null): bool;

    /**
     * Mark the current transaction as finished.
     *
     * @param Carbon|null $finishedAt
     * @return bool
     */
    public function markFinished(?Carbon $finishedAt = null): bool;

    /**
     * User model relationship.
     *
     * @return MorphTo
     */
    public function signer(): MorphTo;
}
