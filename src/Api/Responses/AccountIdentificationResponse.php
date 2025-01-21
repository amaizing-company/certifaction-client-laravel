<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

use AmaizingCompany\CertifactionClient\Api\Responses\Contracts\CertifactionResponse;

class AccountIdentificationResponse extends BaseResponse implements CertifactionResponse
{
    public function getIdentityId(): ?string
    {
        return $this->json('id');
    }

    public function getIdentificationUri(): ?string
    {
        return $this->json('identification_uri');
    }
}
