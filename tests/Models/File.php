<?php

namespace AmaizingCompany\CertifactionClient\Tests\Models;

use AmaizingCompany\CertifactionClient\Concerns\HasCertifactionDocuments;
use AmaizingCompany\CertifactionClient\Contracts\Signable;
use AmaizingCompany\CertifactionClient\Database\Factories\FileFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property string $id
 * @property string $name
 * @property string $disk
 * @property string $path
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class File extends Model implements Signable
{
    use HasFactory;
    use HasUlids;
    use HasCertifactionDocuments;

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

    protected static function newFactory()
    {
        return FileFactory::new();
    }

    public function getDocumentName(): string
    {
        return $this->name;
    }

    public function getFileContents(): string
    {
        return Storage::disk($this->disk)->get($this->path);
    }

    public function storageDisk(): ?string
    {
        return $this->disk;
    }

    public function storageDirectory(): string
    {
        return $this->path;
    }
}
