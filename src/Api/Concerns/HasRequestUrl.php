<?php

namespace AmaizingCompany\CertifactionClient\Api\Concerns;

use Illuminate\Support\Arr;

trait HasRequestUrl
{
    public function getRequestUrl(bool $urlEncoded = true): string
    {
        $value = Arr::get($this->getQueryParams(), 'request_url');

        if ($urlEncoded) {
            return $value;
        }

        return urldecode($value);
    }

    public function requestUrl(string $url): static
    {
        $this->mergeQueryParams('request_url', urlencode($url));

        return $this;
    }
}
