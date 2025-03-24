<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Concerns\HasDocuments;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasQueryParams;
use AmaizingCompany\CertifactionClient\Api\Concerns\HasRequestUrl;
use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Enums\CertifactionLocalEndpoint;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;

final class AddDocumentsToSignatureRequest extends BaseRequest implements Request
{
    use HasDocuments;
    use HasQueryParams;
    use HasRequestUrl;

    public function __construct(string $requestUrl)
    {
        $this->initDocuments();
        $this->requestUrl($requestUrl);
    }

    /**
     * @throws ApiServerUriMissingException
     * @throws ConnectionException
     */
    public function send(): PromiseInterface|Response
    {
        return self::makeRequest(CertifactionEnvironment::LOCAL)
            ->withQueryParameters($this->getQueryParams())
            ->withBody($this->getDocumentsBody())
            ->post(CertifactionLocalEndpoint::SIGNATURE_REQUEST_ADD_DOCUMENTS->value);
    }
}
