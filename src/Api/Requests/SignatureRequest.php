<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\DocumentItem;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\AcceptLanguage;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\CanNotifySigner;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasAdditionalPage;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasAutoSign;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasDigitalTwin;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasDocuments;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasEncryption;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasFileName;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasHash;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasMessage;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasNote;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasPassword;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasPdfA;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasQueryParams;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasSelectiveSigning;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasSignature;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasSigner;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasTransactionId;
use AmaizingCompany\CertifactionClient\Api\Requests\Concerns\HasWebhookUrl;
use AmaizingCompany\CertifactionClient\Api\Requests\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Responses\Contracts\CertifactionResponse;
use AmaizingCompany\CertifactionClient\Api\Responses\SignatureRequestResponse;
use AmaizingCompany\CertifactionClient\CertifactionClient;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;

final class SignatureRequest implements Request
{
    use AcceptLanguage;
    use CanNotifySigner;
    use HasAdditionalPage;
    use HasAutoSign;
    use HasDigitalTwin;
    use HasDocuments;
    use HasEncryption;
    use HasFileName;
    use HasHash;
    use HasMessage;
    use HasNote;
    use HasPassword;
    use HasPdfA;
    use HasQueryParams;
    use HasSelectiveSigning;
    use HasSignature;
    use HasSigner;
    use HasTransactionId;
    use HasWebhookUrl;

    public function getSignerParams(): array
    {
        return [
            'name',
            'first-name',
            'last-name',
            'email',
            'mobile-phone',
            'citizenship',
            'birthday',
            'gender',
            'domicile',
        ];
    }

    public function __construct()
    {
        $this->initDocuments();
    }

    public static function make(): SignatureRequest
    {
        return new SignatureRequest;
    }

    /**
     * @throws ApiServerUriMissingException
     * @throws ConnectionException
     */
    public function send(): SignatureRequestResponse|CertifactionResponse
    {
        $request = CertifactionClient::makeRequest(CertifactionEnvironment::LOCAL)
            ->acceptJson()
            ->withQueryParameters($this->getQueryParams())
            ->withBody($this->getDocumentsBody());

        if (! empty($this->acceptLanguage)) {
            $request->withHeader('Accept-Language', $this->acceptLanguage->value);
        }

        $response = $request->post('/request/create');

        return new SignatureRequestResponse($response->toPsrResponse());
    }
}
