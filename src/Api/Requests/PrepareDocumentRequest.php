<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Concerns\AcceptLanguage;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasAdditionalPage;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasDigitalTwin;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasPdfA;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasQueryParams;
use AmaizingCompany\CertifactionClient\Api\Contracts\CertifactionResponse;
use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Responses\PrepareDocumentResponse;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Enums\DocumentPrepareScope;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Arr;

final class PrepareDocumentRequest extends BaseRequest implements Request
{
    use AcceptLanguage;
    use HasAdditionalPage;
    use HasDigitalTwin;
    use HasPdfA;
    use HasQueryParams;

    protected string $file;

    protected string $fileName;

    public function __construct(string $fileContents, DocumentPrepareScope $scope)
    {
        $this->file = base64_encode($fileContents);
        $this->scope($scope);
    }

    public function getScope(): DocumentPrepareScope
    {
        return DocumentPrepareScope::from(Arr::get($this->getQueryParams(), 'scope'));
    }

    public function isUpload(): bool
    {
        return Arr::get($this->getQueryParams(), 'upload', false);
    }

    public static function make(string $fileContents, DocumentPrepareScope $scope): static
    {
        return new self($fileContents, $scope);
    }

    /**
     * @throws ApiServerUriMissingException
     * @throws ConnectionException
     */
    public function send(): PrepareDocumentResponse|CertifactionResponse
    {
        $request = self::makeRequest(CertifactionEnvironment::LOCAL)
            ->withQueryParameters($this->getQueryParams())
            ->withBody(
                $this->file,
                'application/pdf'
            );

        if (! empty($this->getAcceptLanguage())) {
            $request->withHeader('Accept-Language', $this->getAcceptLanguage()->value);
        }

        $response = $request->post('/prepare');

        return new PrepareDocumentResponse($response->toPsrResponse());
    }

    public function scope(DocumentPrepareScope $scope): static
    {
        $this->mergeQueryParams('scope', $scope->value);

        return $this;
    }

    public function upload(bool $condition = true): static
    {
        $this->mergeQueryParams('upload', $condition);

        return $this;
    }
}
