<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

class PrepareDocumentResponse extends BaseResponse
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
