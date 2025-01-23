<?php

namespace AmaizingCompany\CertifactionClient\Api\Concerns;

use Illuminate\Support\Arr;

trait HasFileName
{
    public function fileName(string $name): static
    {
        $this->mergeQueryParams('filename', $name);

        return $this;
    }

    public function getFileName(): ?string
    {
        return Arr::get($this->getQueryParams(), 'filename');
    }
}
