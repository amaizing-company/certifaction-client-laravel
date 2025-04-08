<?php

namespace AmaizingCompany\CertifactionClient\Database\Factories;

use AmaizingCompany\CertifactionClient\Tests\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'birthdate' => fake()->date,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => fake()->password(),
            'phone' => fake()->e164PhoneNumber(),
        ];
    }
}
