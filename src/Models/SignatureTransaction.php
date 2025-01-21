<?php

namespace AmaizingCompany\CertifactionClient\Models;

use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use AmaizingCompany\CertifactionClient\Enums\SignatureTransactionStatus;
use AmaizingCompany\CertifactionClient\Enums\SignatureType;
use AmaizingCompany\CertifactionClient\Support\DatabaseHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property string id
 * @property string signer_type
 * @property string signer_id
 * @property SignatureType signature_type
 * @property Jurisdiction jurisdiction
 * @property SignatureTransactionStatus status
 * @property string|null request_url
 * @property string|null failure_reason
 * @property Carbon|null requested_at
 * @property Carbon|null finished_at
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class SignatureTransaction extends Model
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

    protected function casts(): array
    {
        return [
            'signature_type' => SignatureType::class,
            'jurisdiction' => Jurisdiction::class,
            'status' => SignatureTransactionStatus::class,
            'requested_at' => Carbon::class,
            'finished_at' => Carbon::class,
        ];
    }

    public function getTable(): string
    {
        return DatabaseHelper::getTableName('signature_transactions');
    }

    public function signer(): MorphTo
    {
        return $this->morphTo();
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, DatabaseHelper::getTableName('signature_transactions_documents'));
    }
}
