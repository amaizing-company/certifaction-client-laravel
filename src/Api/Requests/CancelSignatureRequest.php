<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasFile;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasNote;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasQueryParams;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasSigner;
use AmaizingCompany\CertifactionClient\Api\Requests\Contracts\Request;
use AmaizingCompany\CertifactionClient\CertifactionClient;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

final class CancelSignatureRequest implements Request
{
    use HasFile;
    use HasNote;
    use HasQueryParams;
    use HasSigner;

    const string ENDPOINT_ALL_REQUESTS = '/request/cancel/all';
    const string ENDPOINT_SIGNERS_REQUEST = '/request/cancel';

    public function __construct(string $fileContents)
    {
        $this->file($fileContents);
    }

    public static function make(string $fileContents): CancelSignatureRequest
    {
        return new CancelSignatureRequest($fileContents);
    }

    public function getSignerParams(): array
    {
        return [
            'email'
        ];
    }

    public function getEndpoint(): string
    {
        if ($this->getSigner()?->getEmail()) {
            return CancelSignatureRequest::ENDPOINT_SIGNERS_REQUEST;
        }

        return CancelSignatureRequest::ENDPOINT_ALL_REQUESTS;
    }

    /**
     * @throws ApiServerUriMissingException
     * @throws ConnectionException
     */
    public function send(): bool
    {
        return CertifactionClient::makeRequest(CertifactionEnvironment::LOCAL)
            ->withQueryParameters($this->getQueryParams())
            ->withBody(
                $this->getFileContents(true),
                'application/pdf'
            )
            ->post($this->getEndpoint())
            ->successful();
    }
}
