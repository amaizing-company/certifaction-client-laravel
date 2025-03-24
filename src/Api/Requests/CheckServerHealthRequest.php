<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Responses\CheckServerHealthResponse;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\ConnectionException;

final class CheckServerHealthRequest extends BaseRequest implements Request
{
    public static function make(): CheckServerHealthRequest
    {
        return new self;
    }

    /**
     * @throws ApiServerUriMissingException
     * @throws ConnectionException
     */
    public function send(): CheckServerHealthResponse
    {
        $response = self::makeRequest(CertifactionEnvironment::LOCAL)
            ->acceptJson()
            ->get('/health');

        return new CheckServerHealthResponse($response->toPsrResponse());
    }
}
