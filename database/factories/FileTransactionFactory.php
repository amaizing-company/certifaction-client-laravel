<?php

namespace AmaizingCompany\CertifactionClient\Database\Factories;

use AmaizingCompany\CertifactionClient\Enums\FileTransactionStatus;
use AmaizingCompany\CertifactionClient\Models\Document;
use AmaizingCompany\CertifactionClient\Models\FileTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileTransactionFactory extends Factory
{
    protected $model = FileTransaction::class;

    public function definition(): array
    {
        $status = fake()->randomElement(FileTransactionStatus::class);

        switch ($status) {
            case FileTransactionStatus::PENDING:
                $requestedAt = fake()->dateTime();
                break;

            case FileTransactionStatus::FAILURE:
                $failureReason = fake()->text();
                $finishedAt = fake()->dateTime();
                $requestedAt = fake()->dateTime();
                break;

            case FileTransactionStatus::SUCCESS:
                $finishedAt = fake()->dateTime();
                $requestedAt = fake()->dateTime();
                break;
        }

        return [
            'document_id' => Document::factory(),
            'status' => $status,
            'requested_at' => $requestedAt ?? null,
            'finished_at' => $finishedAt ?? null,
            'file_url' => $this->faker->url(),
            'failure_reason' => $failureReason ?? null,
        ];
    }
}
