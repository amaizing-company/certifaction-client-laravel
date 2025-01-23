<?php

namespace AmaizingCompany\CertifactionClient\Api\DataObjects;

use Illuminate\Support\Carbon;

class Signer
{
    protected ?string $birthdate;

    protected ?string $citizenship;

    protected ?string $domicile;

    protected string $email;

    protected ?string $firstName;

    protected ?string $gender;

    protected ?string $lastName;

    protected ?string $middleNames;

    protected ?string $mobilePhone;

    protected ?string $name;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function birthdate(string|Carbon|null $date): static
    {
        if (is_object($date)) {
            $this->birthdate = $date->format('d.m.Y');

            return $this;
        }

        if (is_string($date)) {
            $this->birthdate = Carbon::create($date)->format('d.m.Y');

            return $this;
        }

        $this->birthdate = $date;

        return $this;
    }

    public function citizenship(string $citizenship): static
    {
        $this->citizenship = $citizenship;

        return $this;
    }

    public function domicile(string $domicile): static
    {
        $this->domicile = $domicile;

        return $this;
    }

    public function email(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function firstName(string $name): static
    {
        $this->firstName = $name;

        return $this;
    }

    public function gender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthdate(): ?Carbon
    {
        return empty($this->birthdate) ? null : Carbon::create($this->birthdate);
    }

    public function getEmail(): ?string
    {
        return $this->email ?? null;
    }

    public function getCitizenship(): ?string
    {
        return $this->citizenship ?? null;
    }

    public function getDomicile(): ?string
    {
        return $this->domicile ?? null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName ?? null;
    }

    public function getGender(): ?string
    {
        return $this->gender ?? null;
    }

    public function getLastName(): ?string
    {
        return $this->lastName ?? null;
    }

    public function getMiddleNames(): ?string
    {
        return $this->middleNames ?? null;
    }

    public function getMobilePhone(): ?string
    {
        return $this->mobilePhone ?? null;
    }

    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    public function lastName(string $name): static
    {
        $this->lastName = $name;

        return $this;
    }

    public function middleNames(string ...$names): static
    {
        $this->middleNames = implode(' ', $names);

        return $this;
    }

    public function mobilePhone(string $number): static
    {
        $this->mobilePhone = $number;

        return $this;
    }

    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'birthdate' => $this->getBirthdate(),
            'citizenship' => $this->getCitizenship(),
            'domicile' => $this->getDomicile(),
            'email' => $this->getEmail(),
            'first-name' => $this->getFirstName(),
            'gender' => $this->getGender(),
            'last-name' => $this->getLastName(),
            'middle-names' => $this->getMiddleNames(),
            'mobile-phone' => $this->getMobilePhone(),
            'name' => $this->getName(),
        ];
    }
}
