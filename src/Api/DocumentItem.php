<?php

namespace AmaizingCompany\CertifactionClient\Api;

class DocumentItem
{
    public function __construct(
        protected string $url,
        protected string $name
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
