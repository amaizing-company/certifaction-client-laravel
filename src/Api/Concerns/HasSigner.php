<?php

namespace AmaizingCompany\CertifactionClient\Api\Concerns;

use AmaizingCompany\CertifactionClient\Api\DataObjects\Signer;

trait HasSigner
{
    protected array $signerParams = [];

    protected ?Signer $signer;

    public function for(Signer $signer): static
    {
        $this->signer = $signer;

        foreach ($signer->toArray() as $key => $value) {
            if (! empty($value) && in_array($key, $this->getSignerParams())) {
                $this->mergeQueryParams($key, $value);
            }
        }

        return $this;
    }

    public function getSigner(): ?Signer
    {
        return $this->signer ?? null;
    }

    public function getSignerParams(): array
    {
        return $this->signerParams ?? [];
    }
}
