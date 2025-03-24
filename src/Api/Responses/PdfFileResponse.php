<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

use Illuminate\Support\Facades\Storage;

class PdfFileResponse extends BaseResponse
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
