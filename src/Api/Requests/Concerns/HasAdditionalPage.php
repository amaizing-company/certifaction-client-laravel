<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests\Concerns;

use Illuminate\Support\Arr;

trait HasAdditionalPage
{
    public function additionalPageForSignature(bool $condition = true): static
    {
        $this->mergeQueryParams('additional-page', $condition);

        return $this;
    }

    public function hasAdditionalPageForSignature(): bool
    {
        return Arr::get($this->getQueryParams(), 'additional-page', true);
    }
}
