<?php

namespace AmaizingCompany\CertifactionClient\Contracts;

use AmaizingCompany\CertifactionClient\Enums\DocumentPrepareScope;
use AmaizingCompany\CertifactionClient\Enums\DocumentStatus;
use Illuminate\Database\Eloquent\Model;
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
 *
 * @mixin Model
 */
interface Document
{
    /**
     * File transaction model relationship.
     */
    public function fileTransactions(): HasMany;

    /**
     * Get the storage path for the document.
     */
    public function getStoragePath(): string;

    /**
     * Get the storage disk for the document.
     */
    public function getStorageDisk(): ?string;

    /**
     * Check if document status is intent.
     */
    public function isIntent(): bool;

    /**
     * Check if document status is prepared.
     */
    public function isPrepared(): bool;

    /**
     * Check if document status is signed.
     */
    public function isSigned(): bool;

    /**
     * Check if signature failed for document.
     */
    public function isSignatureFailed(): bool;

    /**
     * Signable model relationship.
     */
    public function signable(): MorphTo;

    /**
     * Signature transaction model relationship.
     */
    public function signatureTransactions(): BelongsToMany;
}
