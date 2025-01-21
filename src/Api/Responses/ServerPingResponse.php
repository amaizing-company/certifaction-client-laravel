<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

use AmaizingCompany\CertifactionClient\Api\Responses\Contracts\CertifactionResponse;

class ServerPingResponse extends BaseResponse implements CertifactionResponse
{
    public function isServerAlive(): bool
    {
        return $this->successful();
    }
}
