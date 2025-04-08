<?php

namespace AmaizingCompany\CertifactionClient\Models;

use AmaizingCompany\CertifactionClient\Contracts\Account as AccountContract;
use AmaizingCompany\CertifactionClient\Contracts\IdentityTransaction;
use AmaizingCompany\CertifactionClient\Enums\AccountStatus;
use AmaizingCompany\CertifactionClient\Enums\DocumentType;
use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;
use AmaizingCompany\CertifactionClient\Support\DatabaseHelper;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Account extends Model implements AccountContract
{
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
            'status' => AccountStatus::class,
            'admin' => 'boolean',
            'identified' => 'boolean',
        ];
    }

    public function getTable(): string
    {
        return DatabaseHelper::getTableName('accounts');
    }

    public function user(): MorphTo
    {
        return $this->morphTo('user');
    }

    public function markAsIdentified(): bool
    {
        return $this->update(['identified' => true]);
    }

    public function identityTransactions(): HasMany
    {
        return $this->hasMany(app(IdentityTransaction::class)->getMorphClass());
    }

    public function hasPendingIdentificationRequest(): bool
    {
        return $this->identityTransactions()
            ->where('status', IdentificationStatus::PENDING)
            ->exists();
    }

    public function getPendingIdentityTransaction(): IdentityTransaction
    {
        /**
         * @var IdentityTransaction $transaction
         */
        $transaction = $this->identityTransactions()
            ->where('status', IdentificationStatus::PENDING)
            ->first();

        return $transaction;
    }

    public function requestIdentification(DocumentType $documentType, ?Jurisdiction $jurisdiction = null, bool $sync = false): bool
    {
        return CertifactionClient::requestAccountIdentification($this, $documentType, $jurisdiction, $sync);
    }

    public function requestIdentificationStatusCheck(bool $sync = false): bool
    {
        return CertifactionClient::requestAccountIdentificationStatusCheck($this, $sync);
    }

    public function requestDeletion(bool $sync = false): void
    {
        CertifactionClient::requestAccountDeletion($this, $sync);
    }

    public function isInvited(): bool
    {
        return $this->status === AccountStatus::INVITED;
    }

    public function isJoined(): bool
    {
        return $this->status === AccountStatus::JOINED;
    }
}
