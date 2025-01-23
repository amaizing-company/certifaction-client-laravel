<?php

namespace AmaizingCompany\CertifactionClient\Api\Concerns;

use Illuminate\Support\Arr;

trait HasTransactionId
{
    public function getTransactionId(): ?string
    {
        return Arr::get($this->getQueryParams(), 'transaction-id');
    }

    public function transactionId(string $id): static
    {
        $this->mergeQueryParams('transaction-id', $id);

        return $this;
    }
}
