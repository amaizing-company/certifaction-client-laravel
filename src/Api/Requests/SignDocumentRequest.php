<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Concerns\AcceptLanguage;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasAdditionalPage;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasDigitalTwin;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasEncryption;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasFile;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasFileName;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasHash;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasNote;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasPdfA;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasQueryParams;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasSignature;
use AmaizingCompany\CertifactionClient\Api\Contracts\CertifactionResponse;
use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Responses\PdfFileResponse;
use AmaizingCompany\CertifactionClient\CertifactionClient;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\ConnectionException;

final class SignDocumentRequest implements Request
{
    use AcceptLanguage;
    use HasAdditionalPage;
    use HasDigitalTwin;
    use HasEncryption;
    use HasFile;
    use HasFileName;
    use HasHash;
    use HasNote;
    use HasPdfA;
    use HasQueryParams;
    use HasSignature;

    public function __construct(string $fileContents)
    {
        $this->file($fileContents);
    }

    public static function make(string $fileContents): static
    {
        return new self($fileContents);
    }

    /**
     * @throws ApiServerUriMissingException
     * @throws ConnectionException
     */
    public function send(): PdfFileResponse|CertifactionResponse
    {
        $request = CertifactionClient::makeRequest(CertifactionEnvironment::LOCAL)
            ->withQueryParameters($this->getQueryParams())
            ->withBody(
                $this->getFileContents(true),
                'application/pdf'
            );

        if (! empty($this->getAcceptLanguage())) {
            $request->withHeader('Accept-Language', $this->getAcceptLanguage()->value);
        }

        $response = $request->post('/sign');

        return new PdfFileResponse($response->toPsrResponse());
    }
}
