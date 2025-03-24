<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Contracts\CertifactionResponse;
use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Responses\ServerPingResponse;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\ConnectionException;

final class ServerPingRequest extends BaseRequest implements Request
{
    public static function make(): static
    {
        return new self;
    }

    /**
     * @throws ApiServerUriMissingException
     * @throws ConnectionException
     */
    public function send(): ServerPingResponse|CertifactionResponse
    {
        $response = self::makeRequest(CertifactionEnvironment::LOCAL)
            ->get('/ping');

        return new ServerPingResponse($response->toPsrResponse());
    }
}
