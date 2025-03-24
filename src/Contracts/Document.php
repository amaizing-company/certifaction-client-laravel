<?php

namespace AmaizingCompany\CertifactionClient\Contracts;

use AmaizingCompany\CertifactionClient\Enums\DocumentPrepareScope;
use AmaizingCompany\CertifactionClient\Enums\DocumentStatus;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $signable_type
 * @property string $signable_id
 * @property Signable $signable
 * @property string $external_id
 * @property string|null $location
 * @property DocumentStatus $status
 * @property DocumentPrepareScope $scope
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
interface Document
{
    /**
     * File transaction model relationship.
     *
     * @return HasMany
     */
    public function fileTransactions(): HasMany;

    /**
     * Get the storage path for the document.
     *
     * @return string
     */
    public function getStoragePath(): string;

    /**
     * Get the storage disk for the document.
     *
     * @return string|null
     */
    public function getStorageDisk(): ?string;

    /**
     * Check if document status is intent.
     *
     * @return bool
     */
    public function isIntent(): bool;

    /**
     * Check if document status is prepared.
     *
     * @return bool
     */
    public function isPrepared(): bool;

    /**
     * Check if document status is signed.
     *
     * @return bool
     */
    public function isSigned(): bool;

    /**
     * Check if signature failed for document.
     *
     * @return bool
     */
    public function isSignatureFailed(): bool;

    /**
     * Signable model relationship.
     * @return MorphTo
     */
    public function signable(): MorphTo;

    /**
     * Signature transaction model relationship.
     *
     * @return BelongsToMany
     */
    public function signatureTransactions(): BelongsToMany;
}
