<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Contracts\CertifactionResponse;
use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Responses\IdentificationStatusResponse;
use AmaizingCompany\CertifactionClient\CertifactionClient;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\ConnectionException;

final class CheckIdentificationStatusRequest implements Request
{
    protected string $identificationId;

    public function __construct(string $identificationId)
    {
        $this->identificationId($identificationId);
    }

    public function make(string $identificationId): static
    {
        return new self($identificationId);
    }

    public function identificationId(string $id): static
    {
        $this->identificationId = $id;

        return $this;
    }

    public function getIdentificationId(): string
    {
        return $this->identificationId;
    }

    /**
     * @throws ApiServerUriMissingException
     * @throws ConnectionException
     */
    public function send(): IdentificationStatusResponse|CertifactionResponse
    {
        $response = CertifactionClient::makeRequest(CertifactionEnvironment::LOCAL)
            ->acceptJson()
            ->get('/identity/'.$this->getIdentificationId().'/status');

        return new IdentificationStatusResponse($response->toPsrResponse());
    }
}
