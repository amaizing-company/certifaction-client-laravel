<?php

namespace AmaizingCompany\CertifactionClient\Database\Factories;

use AmaizingCompany\CertifactionClient\Tests\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition(): array
    {
        $storage = Storage::fake('local');
        $file = $this->faker->file(__DIR__ . '/../../tests/data/', $storage->path(''), false);

        return [
            'disk' => 'local',
            'path' => $storage->path(''),
            'name' => $file,
        ];
    }
}
