<?php

namespace AmaizingCompany\CertifactionClient\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SignatureTransactionDocument extends Pivot
{
    use HasUlids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->id = $model->newUniqueId();
        });
    }
}
