<?php

namespace AmaizingCompany\CertifactionClient\Database\Factories;

use AmaizingCompany\CertifactionClient\Enums\DocumentType;
use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
use AmaizingCompany\CertifactionClient\Models\Account;
use AmaizingCompany\CertifactionClient\Models\IdentityTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class IdentityTransactionFactory extends Factory
{
    protected $model = IdentityTransaction::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(IdentificationStatus::cases());

        if (in_array($status, [IdentificationStatus::PENDING, IdentificationStatus::VERIFIED, IdentificationStatus::FAILED])) {
            $requestedAt = $this->faker->dateTime();
        }

        if (in_array($status, [IdentificationStatus::FAILED, IdentificationStatus::VERIFIED])) {
            $finishedAt = $this->faker->dateTime();
        }

        return [
            'account_id' => Account::factory(),
            'external_id' => $this->faker->uuid(),
            'status' => $status,
            'identification_method' => $this->faker->randomElement(DocumentType::cases()),
            'identification_uri' => $this->faker->url(),
            'requested_at' => $requestedAt ?? null,
            'finished_at' => $finishedAt ?? null,
        ];
    }
}
