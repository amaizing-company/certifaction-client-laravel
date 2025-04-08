<?php

namespace AmaizingCompany\CertifactionClient\Models;

use AmaizingCompany\CertifactionClient\Contracts\Document;
use AmaizingCompany\CertifactionClient\Contracts\FileTransaction as FileTransactionContract;
use AmaizingCompany\CertifactionClient\Enums\FileTransactionStatus;
use AmaizingCompany\CertifactionClient\Support\DatabaseHelper;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class FileTransaction extends Model implements FileTransactionContract
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

    public function casts(): array
    {
        return [
            'status' => FileTransactionStatus::class,
            'finished_at' => 'datetime',
            'requested_at' => 'datetime',
        ];
    }

    public function getTable(): string
    {
        return DatabaseHelper::getTableName('file_transactions');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(app(Document::class)->getMorphClass());
    }

    public function markFailure(string $failureReason, ?Carbon $finishedAt = null, bool $replicate = true): bool
    {
        $this->status = FileTransactionStatus::FAILURE;
        $this->failure_reason = $failureReason;
        $this->finished_at = $finishedAt ?? Carbon::now();

        $status = $this->save();

        if ($status && $replicate) {
            $replicatedTransaction = $this->replicate();
            $replicatedTransaction->original_transaction_id = $this->hasParent() ? $this->original_transaction_id : $this->id;
            $replicatedTransaction->status = FileTransactionStatus::INTENT;
            $replicatedTransaction->requested_at = null;
            $replicatedTransaction->failure_reason = null;
            $replicatedTransaction->finished_at = null;
            $replicatedTransaction->created_at = Carbon::now();
            $replicatedTransaction->save();
        }

        return $status;
    }

    public function markPending(?Carbon $requestedAt = null): bool
    {
        $this->status = FileTransactionStatus::PENDING;
        $this->requested_at = $requestedAt ?? Carbon::now();

        return $this->save();
    }

    public function markSuccess(?Carbon $finishedAt = null): bool
    {
        $this->status = FileTransactionStatus::SUCCESS;
        $this->finished_at = $finishedAt ?? Carbon::now();

        return $this->save();
    }

    public function originalTransaction(): BelongsTo
    {
        return $this->belongsTo(
            app(FileTransactionContract::class)->getMorphClass(),
            'original_transaction_id',
            'id'
        );
    }

    public function hasParent(): bool
    {
        return $this->originalTransaction()->exists();
    }

    public function hasChildren(): bool
    {
        return $this->childTransactions()->exists();
    }

    public function childTransactions(): HasMany
    {
        return $this->hasMany(
            app(FileTransactionContract::class)->getMorphClass(),
            'original_transaction_id',
            'id'
        );
    }
}
