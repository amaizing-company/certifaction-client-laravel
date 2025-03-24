<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

class ServerPingResponse extends BaseResponse
{
    public function isServerAlive(): bool
    {
        return $this->successful();
    }
}
