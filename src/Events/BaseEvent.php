<?php

namespace AmaizingCompany\CertifactionClient\Events;

use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;

abstract class BaseEvent
{
    public function broadcastWhen(): array
    {
        return CertifactionClient::getConfig('broadcasting');
    }
}
