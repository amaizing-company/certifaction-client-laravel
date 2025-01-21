<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

use AmaizingCompany\CertifactionClient\Api\Responses\Contracts\CertifactionResponse;
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
}
