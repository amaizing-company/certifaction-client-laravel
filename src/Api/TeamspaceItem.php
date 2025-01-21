<?php

namespace AmaizingCompany\CertifactionClient\Api;

class TeamspaceItem
{
    public function __construct(
        protected string $id,
        protected string $name
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
