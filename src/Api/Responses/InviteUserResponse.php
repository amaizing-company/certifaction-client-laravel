<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

class InviteUserResponse extends BaseResponse
{
    public function isInvited(): bool
    {
        return $this->successful();
    }
}
