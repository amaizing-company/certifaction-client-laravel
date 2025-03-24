<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

class AccountIdentificationResponse extends BaseResponse
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
