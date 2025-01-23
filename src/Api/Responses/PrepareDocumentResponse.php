<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

use AmaizingCompany\CertifactionClient\Api\Contracts\CertifactionResponse;

class PrepareDocumentResponse extends BaseResponse implements CertifactionResponse
{
    public function getFileLocation(): ?string
    {
        $value = $this->getHeaderLine('Location');

        return empty($value) ? null : $value;
    }

    public function getFileId(): ?string
    {
        $value = $this->getHeaderLine('File-Id');

        return empty($value) ? null : $value;
    }
}
