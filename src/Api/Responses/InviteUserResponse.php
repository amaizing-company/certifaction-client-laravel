<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

use AmaizingCompany\CertifactionClient\Api\Contracts\CertifactionResponse;

class InviteUserResponse extends BaseResponse implements CertifactionResponse
{
    public function isInvited(): bool
    {
        return $this->successful();
    }
}
