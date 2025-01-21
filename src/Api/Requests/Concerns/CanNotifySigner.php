<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests\Concerns;

use Illuminate\Support\Arr;

trait CanNotifySigner
{
    public function notifySigner(bool $condition = true): static
    {
        $this->mergeQueryParams('send-email', $condition);

        return $this;
    }

    public function shouldNotifySigner(): bool
    {
        return Arr::get($this->getQueryParams(), 'send-email', false);
    }
}
