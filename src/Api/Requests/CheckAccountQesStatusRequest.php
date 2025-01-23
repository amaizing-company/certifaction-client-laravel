<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Concerns\HasQueryParams;
use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Responses\AccountQesStatusResponse;
use AmaizingCompany\CertifactionClient\CertifactionClient;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Arr;

final class CheckAccountQesStatusRequest implements Request
{
    use HasQueryParams;

    public function __construct(string $mobileNumber)
    {
        $this->mobileNumber($mobileNumber);
    }

    public function mobileNumber(string $number): static
    {
        $this->mergeQueryParams('mobile-number', str_replace('+', '', $number));

        return $this;
    }

    public function getMobileNumber(): string
    {
        return Arr::get($this->getQueryParams(), 'mobile-number');
    }

    /**
     * @throws ApiServerUriMissingException
     * @throws ConnectionException
     */
    public function send(): AccountQesStatusResponse
    {
        $response = CertifactionClient::makeRequest(CertifactionEnvironment::LOCAL)
            ->withQueryParameters($this->getQueryParams())
            ->acceptJson()
            ->get('/qes/check');

        return new AccountQesStatusResponse($response->toPsrResponse());
    }
}
