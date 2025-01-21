<?php

namespace AmaizingCompany\CertifactionClient\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AmaizingCompany\CertifactionClient\CertifactionClient
 */
class CertifactionClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \AmaizingCompany\CertifactionClient\CertifactionClient::class;
    }
}
