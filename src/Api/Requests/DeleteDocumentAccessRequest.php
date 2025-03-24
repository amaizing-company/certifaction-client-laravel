<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Concerns\HasFile;
use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;

final class DeleteDocumentAccessRequest extends BaseRequest implements Request
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
        return self::makeRequest(CertifactionEnvironment::LOCAL)
            ->withBody($this->getFileContents(true), 'application/pdf')
            ->post('/delete-access');
    }
}
