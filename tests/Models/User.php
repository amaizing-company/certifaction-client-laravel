<?php

namespace AmaizingCompany\CertifactionClient\Tests\Models;

use AmaizingCompany\CertifactionClient\Concerns\HasCertifactionAccount;
use AmaizingCompany\CertifactionClient\Contracts\CertifactionUser;
use AmaizingCompany\CertifactionClient\Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property Carbon $birthdate;
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class User extends Authenticatable implements CertifactionUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasCertifactionAccount, HasFactory, Notifiable;

    use HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'birthdate',
        'phone',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->id = $model->newUniqueId();
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthdate' => 'date',
        ];
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    public function getBirthdate(): string|Carbon
    {
        return $this->birthdate;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function getMobilePhone(): string
    {
        return $this->phone;
    }
}
