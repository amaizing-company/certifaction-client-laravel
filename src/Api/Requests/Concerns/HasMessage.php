<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests\Concerns;

use Illuminate\Support\Arr;

trait HasMessage
{
    public function message(string $message): static
    {
        $this->mergeQueryParams('message', $message);

        return $this;
    }

    public function getMessage(): ?string
    {
        return Arr::get($this->getQueryParams(), 'message');
    }
}
