<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests\Concerns;

trait HasQueryParams
{
    protected array $queryParams = [];

    protected function mergeQueryParams(string $key, mixed $value): void
    {
        $this->queryParams = array_merge($this->queryParams, [$key => $value]);
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }
}
