<?php

namespace AmaizingCompany\CertifactionClient\Models;

use AmaizingCompany\CertifactionClient\Api\Requests\PrepareDocumentRequest;
use AmaizingCompany\CertifactionClient\Contracts\Signable;
use AmaizingCompany\CertifactionClient\Enums\DocumentPrepareScope;
use AmaizingCompany\CertifactionClient\Enums\DocumentStatus;
use AmaizingCompany\CertifactionClient\Support\DatabaseHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property string id
 * @property string signable_type
 * @property string signable_id
 * @property Signable signable
 * @property string external_id
 * @property string name
 * @property string|null location
 * @property DocumentStatus status
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Document extends Model
{
    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->id = $model->newUniqueId();
        });
    }

    public function casts(): array
    {
        return [
            'status' => DocumentStatus::class,
        ];
    }

    public function getTable(): string
    {
        return DatabaseHelper::getTableName('documents');
    }

    public function signable(): MorphTo
    {
        return $this->morphTo();
    }

    public function signatureTransactions(): BelongsToMany
    {
        return $this->belongsToMany(SignatureTransaction::class, DatabaseHelper::getTableName('documents'));
    }

    public function prepare(DocumentPrepareScope $scope = DocumentPrepareScope::SIGN)
    {
        PrepareDocumentRequest::make($this->signable->getFileContents(), $scope)
            ->upload();
    }
}
