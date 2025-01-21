<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

use AmaizingCompany\CertifactionClient\Api\Responses\Contracts\CertifactionResponse;

class SignatureRequestResponse extends BaseResponse implements CertifactionResponse
{
    public function getRequestUrl(): ?string
    {
        return $this->json('request_url');
    }
}
