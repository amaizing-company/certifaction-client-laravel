<?php

use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
use AmaizingCompany\CertifactionClient\Models\Account;
use AmaizingCompany\CertifactionClient\Models\IdentityTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

test('identity transaction model can be initiated', function () {
    $transaction = IdentityTransaction::factory()->create();

    expect($transaction)
        ->toBeInstanceOf(IdentityTransaction::class);
});

test('identity transaction can relates to account', function () {
    $transaction = IdentityTransaction::factory()->create();

    expect($transaction->account()->first())
        ->toBeInstanceOf(Account::class);
});

test('identity transaction can mark as finished', function () {
    $transaction = IdentityTransaction::factory()->create(['status' => IdentificationStatus::PENDING]);

    expect($transaction->finish(IdentificationStatus::VERIFIED))
        ->toBeTrue()
        ->and($transaction->status)
        ->toBe(IdentificationStatus::VERIFIED)
        ->and($transaction->finished_at)
        ->not()->toBeNull()
        ->and($transaction->last_check_at)
        ->not()->toBeNull();
});

test('identity transaction can update last check', function () {
    $transaction = IdentityTransaction::factory()->create(['last_check_at' => null]);
    $lastCheck = Carbon::now();

    expect($transaction->last_check_at)
        ->toBeNull()
        ->and($transaction->updateLastCheck($lastCheck))
        ->toBeTrue()
        ->and($transaction->last_check_at->format('Y-m-d H:i:s'))
        ->toBe($lastCheck->format('Y-m-d H:i:s'));
});

test('identity transaction can update last request', function () {
    $transaction = IdentityTransaction::factory()->create(['requested_at' => null]);
    $lastRequest = Carbon::now();

    expect($transaction->last_request_at)
        ->toBeNull()
        ->and($transaction->updateLastRequest($lastRequest))
        ->toBeTrue()
        ->and($transaction->requested_at->format('Y-m-d H:i:s'))
        ->toBe($lastRequest->format('Y-m-d H:i:s'));
});

test('identity transaction can mark as pending', function () {
    $transaction = IdentityTransaction::factory()->create(['status' => IdentificationStatus::INTENT, 'external_id' => null, 'identification_uri' => null]);

    expect($transaction->pending('test_id', 'https://test.example'))
        ->toBeTrue()
        ->and($transaction->status)
        ->toBe(IdentificationStatus::PENDING)
        ->and($transaction->external_id)
        ->toBeString()
        ->toBe('test_id')
        ->and($transaction->identification_uri)
        ->toBeString()
        ->toBe('https://test.example');
});

test('identity transaction can check if it´s finished', function () {
    $transaction = IdentityTransaction::factory()->create(['status' => IdentificationStatus::PENDING, 'finished_at' => null]);

    expect($transaction->isFinished())
        ->toBeFalse();

    $transaction->finish(IdentificationStatus::VERIFIED);

    expect($transaction->isFinished())
        ->toBeTrue();
});

test('identity transaction can check if it´s pending', function () {
    $transaction = IdentityTransaction::factory()->create(['status' => IdentificationStatus::INTENT]);

    expect($transaction->isPending())
        ->toBeFalse();

    $transaction->pending('test_id', 'https://test.example');

    expect($transaction->isPending())
        ->toBeTrue();
});

test('identity transaction can check if it´s verified', function () {
    $transaction = IdentityTransaction::factory()->create(['status' => IdentificationStatus::PENDING]);

    expect($transaction->isVerified())
        ->toBeFalse();

    $transaction->finish(IdentificationStatus::VERIFIED);

    expect($transaction->isVerified())
        ->toBeTrue();

    $transaction = IdentityTransaction::factory()->create(['status' => IdentificationStatus::PENDING]);
    $transaction->finish(IdentificationStatus::FAILED);

    expect($transaction->isVerified())
        ->toBeFalse();
});

test('identity transaction can check if it´s failed', function () {
    $transaction = IdentityTransaction::factory()->create(['status' => IdentificationStatus::PENDING]);

    expect($transaction->isFailed())
        ->toBeFalse();

    $transaction->finish(IdentificationStatus::FAILED);

    expect($transaction->isFailed())
        ->toBeTrue();

    $transaction = IdentityTransaction::factory()->create(['status' => IdentificationStatus::PENDING]);
    $transaction->finish(IdentificationStatus::VERIFIED);

    expect($transaction->isFailed())
        ->toBeFalse();
});

test('identity transaction can check if it´s intent', function () {
    $transaction = IdentityTransaction::factory()->create(['status' => IdentificationStatus::INTENT]);

    expect($transaction->isIntent())
        ->toBeTrue();

    $transaction->pending('test_id', 'https://test.example');

    expect($transaction->isIntent())
        ->toBeFalse();
});
