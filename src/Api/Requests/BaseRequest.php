<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class BaseRequest
{
    /**
     * @throws ApiServerUriMissingException
     */
    protected static function makeRequest(CertifactionEnvironment $environment, bool $withAuth = true): PendingRequest
    {
        $request = Http::baseUrl($environment->getUri());

        if ($withAuth) {
            $request->withHeader('Authorization', CertifactionClient::getApiKey());
        }

        return $request;
    }
}
