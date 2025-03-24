<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Concerns\HasQueryParams;
use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Responses\PdfFileResponse;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Arr;

final class DownloadDocumentRequest extends BaseRequest implements Request
{
    use HasQueryParams;

    public function __construct(string $fileUrl)
    {
        $this->fileUrl($fileUrl);
    }

    public static function make(string $fileUrl): static
    {
        return new self($fileUrl);
    }

    public function fileUrl(string $url): static
    {
        $this->mergeQueryParams('file', $url);

        return $this;
    }

    public function getFileUrl(): string
    {
        return Arr::get($this->getQueryParams(), 'file');
    }

    public function password(string $password): static
    {
        $this->mergeQueryParams('password', $password);

        return $this;
    }

    public function getPassword(): ?string
    {
        return Arr::get($this->getQueryParams(), 'password');
    }

    /**
     * @throws ApiServerUriMissingException
     * @throws ConnectionException
     */
    public function send(): PdfFileResponse
    {
        $response = self::makeRequest(CertifactionEnvironment::LOCAL)
            ->withQueryParameters($this->getQueryParams())
            ->get('/download');

        return new PdfFileResponse($response->toPsrResponse());
    }
}
