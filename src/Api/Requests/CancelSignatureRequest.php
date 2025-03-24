<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Concerns\HasFile;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasNote;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasQueryParams;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasSigner;
use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Enums\CertifactionLocalEndpoint;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\ConnectionException;

final class CancelSignatureRequest extends BaseRequest implements Request
{
    use HasFile;
    use HasNote;
    use HasQueryParams;
    use HasSigner;

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
            'email',
        ];
    }

    public function getEndpoint(): string
    {
        if ($this->getSigner()?->getEmail()) {
            return CertifactionLocalEndpoint::SIGNATURE_REQUEST_CANCEL_SIGNERS->value;
        }

        return CertifactionLocalEndpoint::SIGNATURE_REQUEST_CANCEL_ALL->value;
    }

    /**
     * @throws ApiServerUriMissingException
     * @throws ConnectionException
     */
    public function send(): bool
    {
        return self::makeRequest(CertifactionEnvironment::LOCAL)
            ->withQueryParameters($this->getQueryParams())
            ->withBody(
                $this->getFileContents(true),
                'application/pdf'
            )
            ->post($this->getEndpoint())
            ->successful();
    }
}
