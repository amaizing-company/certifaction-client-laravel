<?php

namespace AmaizingCompany\CertifactionClient\Models;

use AmaizingCompany\CertifactionClient\Contracts\Document;
use AmaizingCompany\CertifactionClient\Contracts\SignatureTransaction as SignatureTransactionContract;
use AmaizingCompany\CertifactionClient\Database\Factories\SignatureTransactionFactory;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use AmaizingCompany\CertifactionClient\Enums\SignatureTransactionStatus;
use AmaizingCompany\CertifactionClient\Enums\SignatureType;
use AmaizingCompany\CertifactionClient\Support\DatabaseHelper;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class SignatureTransaction extends Model implements SignatureTransactionContract
{
    use HasFactory;
    use HasUlids;

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
            'requested_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    protected static function newFactory()
    {
        return SignatureTransactionFactory::new();
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
        return $this->belongsToMany(
            app(Document::class)->getMorphClass(),
            DatabaseHelper::getTableName('signature_transactions_documents'),
            'signature_transaction_id',
            'document_id'
        )->using(SignatureTransactionDocument::class);
    }

    public function getWebhookUrl(): string
    {
        $encryptedId = Crypt::encrypt($this->id);

        return $this->documents->first()->signable->getWebhookUrl()."/$encryptedId";
    }

    public function markPending(string $requestUrl, ?Carbon $requestedAt = null): bool
    {
        $this->status = SignatureTransactionStatus::PENDING;
        $this->request_url = $requestUrl;
        $this->requested_at = $requestedAt ?? Carbon::now();

        return $this->save();
    }

    public function markFinished(?Carbon $finishedAt = null): bool
    {
        $this->status = SignatureTransactionStatus::SUCCEED;
        $this->finished_at = $finishedAt ?? Carbon::now();

        return $this->save();
    }

    public function markFailed(string $failureReason, ?Carbon $finishedAt = null): bool
    {
        $this->status = SignatureTransactionStatus::FAILED;
        $this->failure_reason = $failureReason;
        $this->finished_at = $finishedAt ?? Carbon::now();

        return $this->save();
    }
}
