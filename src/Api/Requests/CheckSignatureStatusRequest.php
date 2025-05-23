<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Concerns\HasQueryParams;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasRequestUrl;
use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Responses\CheckSignatureStatusResponse;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\ConnectionException;

final class CheckSignatureStatusRequest extends BaseRequest implements Request
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
    public function send(): CheckSignatureStatusResponse
    {
        $response = self::makeRequest(CertifactionEnvironment::LOCAL)
            ->withQueryParameters($this->getQueryParams())
            ->acceptJson()
            ->get('/request/status');

        return new CheckSignatureStatusResponse($response->toPsrResponse());
    }
}
