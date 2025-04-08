<?php

namespace AmaizingCompany\CertifactionClient\Database\Factories;

use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use AmaizingCompany\CertifactionClient\Enums\SignatureTransactionStatus;
use AmaizingCompany\CertifactionClient\Enums\SignatureType;
use AmaizingCompany\CertifactionClient\Models\SignatureTransaction;
use AmaizingCompany\CertifactionClient\Tests\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SignatureTransactionFactory extends Factory
{
    protected $model = SignatureTransaction::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(SignatureTransactionStatus::cases());

        if (in_array($status, [SignatureTransactionStatus::PENDING, SignatureTransactionStatus::FAILED, SignatureTransactionStatus::SUCCEED])) {
            $requestedAt = $this->faker->dateTime();
        }

        if (in_array($status, [SignatureTransactionStatus::SUCCEED, SignatureTransactionStatus::FAILED])) {
            $finishedAt = $this->faker->dateTime();
        }

        if ($status === SignatureTransactionStatus::FAILED) {
            $failureReason = $this->faker->sentence();
        }

        return [
            'signer_type' => app(User::class)->getMorphClass(),
            'signer_id' => User::factory(),
            'signature_type' => $this->faker->randomElement(SignatureType::cases()),
            'jurisdiction' => $this->faker->randomElement(Jurisdiction::cases()),
            'status' => $status,
            'request_url' => $this->faker->url(),
            'failure_reason' => $failureReason ?? null,
            'requested_at' => $requestedAt ?? null,
            'finished_at' => $finishedAt ?? null,
        ];
    }
}
