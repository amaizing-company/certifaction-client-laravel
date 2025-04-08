<?php

namespace AmaizingCompany\CertifactionClient\Database\Factories;

use AmaizingCompany\CertifactionClient\Enums\DocumentPrepareScope;
use AmaizingCompany\CertifactionClient\Enums\DocumentStatus;
use AmaizingCompany\CertifactionClient\Models\Document;
use AmaizingCompany\CertifactionClient\Tests\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'signable_type' => app(File::class)->getMorphClass(),
            'signable_id' => File::factory(),
            'external_id' => $this->faker->uuid,
            'location' => $this->faker->url(),
            'status' => $this->faker->randomElement(DocumentStatus::cases()),
            'scope' => $this->faker->randomElement(DocumentPrepareScope::cases()),
        ];
    }
}
