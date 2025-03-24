<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

class SignatureRequestResponse extends BaseResponse
{
    public function getRequestUrl(): ?string
    {
        return $this->json('request_url');
    }
}
