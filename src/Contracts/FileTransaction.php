<?php

namespace AmaizingCompany\CertifactionClient\Contracts;

use AmaizingCompany\CertifactionClient\Enums\FileTransactionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $document_id
 * @property Document $document
 * @property FileTransactionStatus $status
 * @property string $file_url
 * @property string|null $failure_reason
 * @property Carbon|null $requested_at
 * @property Carbon|null $finished_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @mixin Model
 */
interface FileTransaction
{
    /**
     * Certifaction document model relation.
     */
    public function document(): BelongsTo;

    /**
     * Mark transaction as failed.
     */
    public function markFailure(string $failureReason, ?Carbon $finishedAt = null, bool $replicate = true): bool;

    /**
     * Mark transaction as pending.
     */
    public function markPending(?Carbon $requestedAt = null): bool;

    /**
     * Mark transaction as succeeded.
     */
    public function markSuccess(?Carbon $finishedAt = null): bool;
}
