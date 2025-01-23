<?php

namespace AmaizingCompany\CertifactionClient\Api\Concerns;

use Illuminate\Support\Arr;

trait HasDigitalTwin
{
    public function digitalTwin(bool $condition = true): static
    {
        $this->mergeQueryParams('digital-twin', $condition);

        return $this;
    }

    public function digitalTwinQrPageNumber(int $page): static
    {
        $this->mergeQueryParams('qr-page', $page);

        return $this;
    }

    public function digitalTwinQrPosition(float|int $x, float|int $y): static
    {
        $this->mergeQueryParams('qr-llx', $x);
        $this->mergeQueryParams('qr-lly', $y);

        return $this;
    }

    public function getDigitalTwinPageNumber(): ?int
    {
        return Arr::get($this->getQueryParams(), 'qr-page');
    }

    public function getDigitalTwinQrPositionX(): float|int|null
    {
        return Arr::get($this->getQueryParams(), 'qr-llx');
    }

    public function getDigitalTwinQrPositionY(): float|int|null
    {
        return Arr::get($this->getQueryParams(), 'qr-lly');
    }

    public function hasDigitalTwin(): bool
    {
        return Arr::get($this->getQueryParams(), 'digital-twin', false);
    }
}
