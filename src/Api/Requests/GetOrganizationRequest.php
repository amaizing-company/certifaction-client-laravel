<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Responses\GetOrganizationResponse;
use AmaizingCompany\CertifactionClient\CertifactionClient;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\ConnectionException;

final class GetOrganizationRequest implements Request
{
    public static function make(): GetOrganizationRequest
    {
        return new GetOrganizationRequest;
    }

    /**
     * @throws ApiServerUriMissingException
     * @throws ConnectionException
     */
    public function send()
    {
        $response = CertifactionClient::makeRequest(CertifactionEnvironment::ADMIN)
            ->acceptJson()
            ->get('/organization');

        return new GetOrganizationResponse($response->toPsrResponse());
    }
}
