<?php

namespace AmaizingCompany\CertifactionClient\Models;

use AmaizingCompany\CertifactionClient\Contracts\Account;
use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;
use AmaizingCompany\CertifactionClient\Contracts\IdentityTransaction as IdentityTransactionContract;
use AmaizingCompany\CertifactionClient\Enums\DocumentType;
use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
use AmaizingCompany\CertifactionClient\Observers\IdentityTransactionObserver;
use AmaizingCompany\CertifactionClient\Support\DatabaseHelper;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

#[ObservedBy(IdentityTransactionObserver::class)]
class IdentityTransaction extends Model implements IdentityTransactionContract
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
            'identification_method' => DocumentType::class,
            'status' => IdentificationStatus::class,
            'requested_at' => 'datetime',
            'last_check_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function getTable(): string
    {
        return DatabaseHelper::getTableName('identity_transactions');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(app(Account::class));
    }

    public function finish(IdentificationStatus $status, ?Carbon $lastCheck = null): bool
    {
        return $this->update([
            'status' => $status,
            'last_check_at' => $lastCheck ?? Carbon::now(),
            'finished_at' => Carbon::now(),
        ]);
    }

    public function updateLastCheck(?Carbon $lastCheck = null): bool
    {
        return $this->update(['last_check_at' => $lastCheck ?? Carbon::now()]);
    }

    public function updateLastRequest(?Carbon $lastRequest = null): bool
    {
        return $this->update(['last_request_at' => $lastRequest ?? Carbon::now()]);
    }

    public function pending(string $identificationId, string $identificationUrl): bool
    {
        return $this->update([
            'status' => IdentificationStatus::PENDING,
            'external_id' => $identificationId,
            'identification_url' => $identificationUrl,
        ]);
    }

    public function isFinished(): bool
    {
        return $this->finished_at !== null;
    }

    public function isPending(): bool
    {
        return $this->status === IdentificationStatus::PENDING;
    }

    public function isVerified(): bool
    {
        return $this->status === IdentificationStatus::VERIFIED;
    }

    public function isFailed(): bool
    {
        return $this->status === IdentificationStatus::FAILED;
    }

    public function isIntent(): bool
    {
        return $this->status === IdentificationStatus::INTENT;
    }

    public function requestStatusCheck(): bool
    {
        return CertifactionClient::requestIdentificationStatusCheck($this);
    }
}
