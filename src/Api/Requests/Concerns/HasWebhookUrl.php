<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests\Concerns;

use Illuminate\Support\Arr;

trait HasWebhookUrl
{
    public function getWebhookUrl(): ?string
    {
        return Arr::get($this->getQueryParams(), 'webhook-url');
    }

    public function webhookUrl(string $url): static
    {
        $this->mergeQueryParams('webhook-url', $url);

        return $this;
    }
}
