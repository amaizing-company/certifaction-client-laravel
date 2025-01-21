<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests\Concerns;

use Illuminate\Support\Arr;

trait HasHash
{
    public function withHash(string $hash): static
    {
        $this->mergeQueryParams('hash', $hash);

        return $this;
    }

    public function getHash(): ?string
    {
        return Arr::get($this->getQueryParams(), 'hash');
    }
}
