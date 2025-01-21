<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests\Concerns;

trait HasFile
{
    protected string $file;

    public function file(string $contents): static
    {
        $this->file = base64_encode($contents);

        return $this;
    }

    public function getFileContents(bool $encoded = false): string
    {
        return empty($this->file) ?: ($encoded ? $this->file : base64_decode($this->file));
    }
}
