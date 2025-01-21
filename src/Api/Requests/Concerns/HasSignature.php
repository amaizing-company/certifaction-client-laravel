<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests\Concerns;

use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use AmaizingCompany\CertifactionClient\Enums\SignatureType;
use Illuminate\Support\Arr;

trait HasSignature
{
    public function getJurisdiction(): ?Jurisdiction
    {
        return Arr::get($this->getQueryParams(), 'jurisdiction');
    }

    public function getSignatureType(): ?SignatureType
    {
        return Arr::get($this->getQueryParams(), 'signature-type');
    }

    public function getSignatureHeight(): ?int
    {
        return Arr::get($this->getQueryParams(), 'height');
    }

    public function getSignaturePageNumber(): ?int
    {
        return Arr::get($this->getQueryParams(), 'page');
    }

    public function getSignaturePositionX(): float|int|null
    {
        return Arr::get($this->getQueryParams(), 'position-x');
    }

    public function getSignaturePositionY(): float|int|null
    {
        return Arr::get($this->getQueryParams(), 'position-y');
    }

    public function isPrepared(): bool
    {
        return !Arr::get($this->getQueryParams(), 'no-prepare', false);
    }

    public function jurisdiction(Jurisdiction $jurisdiction): static
    {
        $this->mergeQueryParams('jurisdiction', $jurisdiction);

        return $this;
    }

    public function noPrepare(bool $condition = true): static
    {
        $this->mergeQueryParams('no-prepare', $condition);

        return $this;
    }

    public function signatureHeight(float|int $height): static
    {
        $this->mergeQueryParams('height', $height);

        return $this;
    }

    public function signaturePageNumber(int $page): static
    {
        $this->mergeQueryParams('page', $page);

        return $this;
    }

    public function signaturePosition(float|int $x, float|int $y): static
    {
        $this->mergeQueryParams('position-x', $x);
        $this->mergeQueryParams('position-y', $y);

        return $this;
    }

    public function signatureType(SignatureType $type): static
    {
        $this->mergeQueryParams('signature-type', $type);

        return $this;
    }
}
