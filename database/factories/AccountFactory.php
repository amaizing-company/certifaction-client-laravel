<?php

namespace AmaizingCompany\CertifactionClient\Database\Factories;

use AmaizingCompany\CertifactionClient\Enums\AccountStatus;
use AmaizingCompany\CertifactionClient\Models\Account;
use AmaizingCompany\CertifactionClient\Tests\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition(): array
    {
        return [
            'user_type' => app(User::class)->getMorphClass(),
            'user_id' => User::factory(),
            'invite_email' => fake()->safeEmail(),
            'external_id' => fake()->numberBetween(100000, 999999),
            'external_uid' => fake()->uuid(),
            'role_id' => fake()->text(10),
            'status' => fake()->randomElement(AccountStatus::cases()),
            'admin' => fake()->boolean(),
            'identified' => fake()->boolean(),
        ];
    }
}
