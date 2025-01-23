<?php

namespace AmaizingCompany\CertifactionClient\Api\Concerns;

use AmaizingCompany\CertifactionClient\Enums\Language;

trait AcceptLanguage
{
    protected ?Language $acceptLanguage;

    public function acceptLanguage(Language $language): static
    {
        $this->acceptLanguage = $language;

        return $this;
    }

    public function getAcceptLanguage(): ?Language
    {
        return $this->acceptLanguage ?? null;
    }
}
