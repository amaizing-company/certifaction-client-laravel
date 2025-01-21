<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests\Concerns;

use Illuminate\Support\Arr;

trait HasPassword
{
    public function hasPassword(): bool
    {
        return Arr::get($this->getQueryParams(), 'with-password', false);
    }

    public function withPassword(string $key): static
    {
        $this->withEncryption($key);
        $this->mergeQueryParams('with-password', true);

        return $this;
    }
}
