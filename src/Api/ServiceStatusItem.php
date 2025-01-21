<?php

namespace AmaizingCompany\CertifactionClient\Api;

class ServiceStatusItem
{
    public function __construct(
        protected string $service,
        protected string $description,
        protected string $status,
    ) {}

    public function getServiceName(): string
    {
        return $this->service;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isHealthy(): bool
    {
        return strtolower($this->getStatus()) === 'up';
    }
}
