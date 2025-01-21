<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests\Concerns;

use Illuminate\Support\Arr;

trait HasPdfA
{
    public function pdfA(bool $condition = true): static
    {
        $this->mergeQueryParams('pdf-a', $condition);

        return $this;
    }

    public function isPdfA(): bool
    {
        return Arr::get($this->getQueryParams(), 'pdf-a', false);
    }
}
