<?php

namespace AmaizingCompany\CertifactionClient\Models;

use AmaizingCompany\CertifactionClient\Api\Requests\PrepareDocumentRequest;
use AmaizingCompany\CertifactionClient\Contracts\Document as DocumentContract;
use AmaizingCompany\CertifactionClient\Contracts\FileTransaction;
use AmaizingCompany\CertifactionClient\Contracts\SignatureTransaction;
use AmaizingCompany\CertifactionClient\Enums\DocumentPrepareScope;
use AmaizingCompany\CertifactionClient\Enums\DocumentStatus;
use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;
use AmaizingCompany\CertifactionClient\Support\DatabaseHelper;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Document extends Model implements DocumentContract
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
            'status' => DocumentStatus::class,
            'scope' => DocumentPrepareScope::class,
        ];
    }

    public function getTable(): string
    {
        return DatabaseHelper::getTableName('documents');
    }

    public function getStoragePath(): string
    {
        $storageDir = $this->signable->storageDirectory()
            ?? CertifactionClient::getConfig('storage_directory');

        $fileName = $this->signable->getDocumentName();

        if (! Str::endsWith($fileName, '.pdf')) {
            $fileName .= '.pdf';
        }

        if (Str::startsWith($fileName, '/')) {
            $fileName = Str::ltrim($fileName, '/');
        }

        if (Str::endsWith($storageDir, '/')) {
            $storageDir = Str::rtrim($storageDir, '/');
        }

        return "$storageDir/$fileName";
    }

    public function getStorageDisk(): ?string
    {
        return $this->signable->storageDisk() ?? CertifactionClient::getConfig('storage_disk');
    }

    public function signable(): MorphTo
    {
        return $this->morphTo();
    }

    public function signatureTransactions(): BelongsToMany
    {
        return $this->belongsToMany(
            app(SignatureTransaction::class)->getMorphClass(),
            DatabaseHelper::getTableName('signature_transactions_documents'),
            'document_id',
            'signature_transaction_id'
        )->using(SignatureTransactionDocument::class);
    }

    public function fileTransactions(): HasMany
    {
        return $this->hasMany(app(FileTransaction::class)->getMorphClass());
    }

    public function prepare(DocumentPrepareScope $scope = DocumentPrepareScope::SIGN): void
    {
        PrepareDocumentRequest::make($this->signable->getFileContents(), $scope)
            ->upload();
    }

    public function isIntent(): bool
    {
        return $this->status === DocumentStatus::INTENT;
    }

    public function isPrepared(): bool
    {
        return $this->status === DocumentStatus::PREPARED;
    }

    public function isSigned(): bool
    {
        return $this->status === DocumentStatus::SIGNED;
    }

    public function isSignatureFailed(): bool
    {
        return $this->status === DocumentStatus::SIGNATURE_FAILED;
    }
}
