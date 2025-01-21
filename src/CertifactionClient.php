<?php

namespace AmaizingCompany\CertifactionClient;

use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class CertifactionClient
{
    protected static function getApiKey(): string
    {
        return config('certifaction-client-laravel.api.auth.key');
    }

    /**
     * @throws ApiServerUriMissingException
     */
    public static function makeRequest(CertifactionEnvironment $environment, bool $withAuth = true): PendingRequest
    {
        $request = Http::baseUrl($environment->getUri());

        if ($withAuth) {
            $request->withHeader('Authorization', static::getApiKey());
        }

        return $request;
    }
}
