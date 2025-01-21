<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\AcceptLanguage;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasAdditionalPage;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasDigitalTwin;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasEncryption;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasFile;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasFileName;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasHash;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasNote;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasPdfA;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasQueryParams;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasSignature;
use AmaizingCompany\CertifactionClient\Api\Requests\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Responses\Contracts\CertifactionResponse;
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
        return new static($fileContents);
    }

    /**
     * @return PdfFileResponse|CertifactionResponse
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

        if (!empty($this->getAcceptLanguage())) {
            $request->withHeader('Accept-Language', $this->getAcceptLanguage()->value);
        }

        $response = $request->post('/sign');

        return new PdfFileResponse($response->toPsrResponse());
    }
}
