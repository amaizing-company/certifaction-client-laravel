<?php

namespace AmaizingCompany\CertifactionClient\Api;

class RoleItem
{
    protected string $id;
    protected string $name;
    protected bool $default = false;
    protected bool $admin = false;

    public function __construct(string $id, string $name)
    {
        $this->id($id);
        $this->name($name);
    }

    public function id(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function default(bool $condition = true): static
    {
        $this->default = $condition;

        return $this;
    }

    public function admin(bool $condition = true): static
    {
        $this->admin = $condition;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isDefault(): bool
    {
        return $this->default;
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }
}
