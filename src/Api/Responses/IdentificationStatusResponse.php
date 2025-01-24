<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

use AmaizingCompany\CertifactionClient\Api\Contracts\CertifactionResponse;
use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;

class IdentificationStatusResponse extends BaseResponse implements CertifactionResponse
{
    public function getIdentificationId(): ?string
    {
        return $this->json('id');
    }

    public function getIdentificationStatus(): ?IdentificationStatus
    {
        return IdentificationStatus::tryFrom($this->json('status'));
    }

    public function getIdentificationMethod(): ?string
    {
        return $this->json('identification_method');
    }
}
