<?php

namespace AmaizingCompany\CertifactionClient\Api\DataObjects;

use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;

class QesStatusItem
{
    protected Jurisdiction $jurisdiction;

    protected ?string $signatureLevel = null;

    protected string $status;

    public function __construct(string|Jurisdiction $jurisdiction, string $status, ?string $signatureLevel = null)
    {
        $this->jurisdiction($jurisdiction)
            ->status($status);

        if (! empty($signatureLevel)) {
            $this->signatureLevel($signatureLevel);
        }
    }

    public function getJurisdiction(): Jurisdiction
    {
        return $this->jurisdiction;
    }

    public function getSignatureLevel(): ?string
    {
        return $this->signatureLevel;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function jurisdiction(string|Jurisdiction $jurisdiction): static
    {
        if (is_string($jurisdiction)) {
            $jurisdiction = Jurisdiction::tryFrom($jurisdiction);
        }

        $this->jurisdiction = $jurisdiction;

        return $this;
    }

    public function signatureLevel(string $level): static
    {
        $this->signatureLevel = $level;

        return $this;
    }

    public function status(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
