<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Concerns\AcceptLanguage;
use AmaizingCompany\CertifactionClient\Api\Concerns\CanNotifySigner;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasAdditionalPage;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasAutoSign;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasDigitalTwin;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasDocuments;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasEncryption;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasFileName;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasHash;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasMessage;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasNote;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasPassword;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasPdfA;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasQueryParams;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasSelectiveSigning;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasSignature;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasSigner;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasTransactionId;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasWebhookUrl;
use AmaizingCompany\CertifactionClient\Api\Contracts\CertifactionResponse;
use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Responses\SignatureRequestResponse;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\ConnectionException;

final class SignatureRequest extends BaseRequest implements Request
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
        $request = self::makeRequest(CertifactionEnvironment::LOCAL)
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
