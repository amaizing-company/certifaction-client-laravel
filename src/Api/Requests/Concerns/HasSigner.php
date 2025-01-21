<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests\Concerns;

use AmaizingCompany\CertifactionClient\Api\Signer;

trait HasSigner
{
    protected array $signerParams = [];
    protected ?Signer $signer;

    public function for(Signer $signer): static
    {
        $this->signer = $signer;

        foreach ($signer->toArray() as $key => $value) {
            if (!empty($value) && in_array($key, $this->getSignerParams())) {
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
