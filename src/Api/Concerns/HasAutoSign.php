<?php

namespace AmaizingCompany\CertifactionClient\Api\Concerns;

use Illuminate\Support\Arr;

trait HasAutoSign
{
    public function autoSign(bool $condition = true): static
    {
        $this->mergeQueryParams('auto-sign', $condition);

        return $this;
    }

    public function hasAutoSign(): bool
    {
        return Arr::get($this->getQueryParams(), 'auto-sign', false);
    }
}
