<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

use AmaizingCompany\CertifactionClient\Api\Responses\Contracts\CertifactionResponse;
use Illuminate\Support\Facades\Storage;

class PdfFileResponse extends BaseResponse implements CertifactionResponse
{
    public function getFileContents(): ?string
    {
        return $this->body();
    }

    public function saveFile(string $path, ?string $disk = null): bool
    {
        return Storage::disk($disk)->put($path, $this->body());
    }
}
