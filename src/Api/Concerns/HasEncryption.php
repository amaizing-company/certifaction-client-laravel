<?php

namespace AmaizingCompany\CertifactionClient\Api\Concerns;

use Illuminate\Support\Arr;

trait HasEncryption
{
    public function withEncryption(string $key): static
    {
        $this->mergeQueryParams('encryption-key', $key);
        $this->mergeQueryParams('password-encryption', 'xor-b58');

        return $this;
    }

    public function getEncryptionKey(): ?string
    {
        return Arr::get($this->getQueryParams(), 'encryption-key');
    }

    public function getPasswordEncryption(): ?string
    {
        return Arr::get($this->getQueryParams(), 'password-encryption');
    }
}
