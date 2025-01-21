<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests\Concerns;

use Illuminate\Support\Arr;

trait HasSelectiveSigning
{
    public function hasSelectiveSigning(): bool
    {
        return Arr::get($this->getQueryParams(), 'selective-signing', false);
    }

    public function selectiveSigning(bool $condition = true): static
    {
        $this->mergeQueryParams('selective-signing', $condition);

        return $this;
    }
}
