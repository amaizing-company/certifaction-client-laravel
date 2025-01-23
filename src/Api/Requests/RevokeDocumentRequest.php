<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasFile;
use AmaizingCompany\CertifactionClient\Api\Requests\Contracts\Request;
use AmaizingCompany\CertifactionClient\CertifactionClient;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;

class RevokeDocumentRequest implements Request
{
    use HasFile;

    public function __construct(string $fileContents)
    {
        $this->file($fileContents);
    }

    /**
     * @throws ApiServerUriMissingException
     * @throws ConnectionException
     */
    public function send(): PromiseInterface|Response
    {
        return CertifactionClient::makeRequest(CertifactionEnvironment::LOCAL)
            ->withBody($this->getFileContents(true), 'application/pdf')
            ->post('/revoke');
    }
}
