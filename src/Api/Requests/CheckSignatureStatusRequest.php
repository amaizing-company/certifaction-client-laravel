<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Concerns\HasQueryParams;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasRequestUrl;
use AmaizingCompany\CertifactionClient\Api\Contracts\CertifactionResponse;
use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Responses\CheckSignatureStatusResponse;
use AmaizingCompany\CertifactionClient\CertifactionClient;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\ConnectionException;

final class CheckSignatureStatusRequest implements Request
{
    use HasQueryParams;
    use HasRequestUrl;

    public function __construct(string $requestUrl)
    {
        $this->requestUrl($requestUrl);
    }

    public static function make(string $requestUrl): CheckSignatureStatusRequest
    {
        return new self($requestUrl);
    }

    /**
     * @throws ApiServerUriMissingException
     * @throws ConnectionException
     */
    public function send(): CheckSignatureStatusResponse|CertifactionResponse
    {
        $response = CertifactionClient::makeRequest(CertifactionEnvironment::LOCAL)
            ->withQueryParameters($this->getQueryParams())
            ->acceptJson()
            ->get('/request/status');

        return new CheckSignatureStatusResponse($response->toPsrResponse());
    }
}
