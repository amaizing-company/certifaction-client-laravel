<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasQueryParams;
use AmaizingCompany\CertifactionClient\Api\Requests\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Responses\CheckSignatureStatusResponse;
use AmaizingCompany\CertifactionClient\Api\Responses\Contracts\CertifactionResponse;
use AmaizingCompany\CertifactionClient\CertifactionClient;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Arr;

final class CheckSignatureStatusRequest implements Request
{
    use HasQueryParams;

    public function __construct(string $requestUrl)
    {
        $this->mergeQueryParams('request_url', urlencode($requestUrl));
    }

    public static function make(string $requestUrl): static
    {
        return new self($requestUrl);
    }

    public function getRequestUrl(bool $urlEncoded = true): string
    {
        $value = Arr::get($this->getQueryParams(), 'request_url');

        if ($urlEncoded) {
            return $value;
        }

        return urldecode($value);
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
