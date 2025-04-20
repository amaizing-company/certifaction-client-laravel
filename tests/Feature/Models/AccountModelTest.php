<?php

use AmaizingCompany\CertifactionClient\Enums\AccountStatus;
use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
use AmaizingCompany\CertifactionClient\Models\Account;
use AmaizingCompany\CertifactionClient\Models\IdentityTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->group('package', 'models');

uses(RefreshDatabase::class);

test('account model can be initiated', function () {
    $account = Account::factory()->create();

    expect($account)
        ->toBeInstanceOf(Account::class);
});

it('can mark account as identified', function () {
    $account = Account::factory()->create(['identified' => false]);
    $account->markAsIdentified();

    expect($account->identified)
        ->toBeTrue();
});

it('can check if account status is invited', function () {
    $account = Account::factory()->create(['status' => AccountStatus::INVITED]);

    expect($account->isInvited())
        ->toBeTrue();

    $account->update(['status' => AccountStatus::JOINED]);

    expect($account->isInvited())
        ->toBeFalse();
});

it('can check if account status is joined', function () {
    $account = Account::factory()->create(['status' => AccountStatus::JOINED]);

    expect($account->isJoined())
        ->toBeTrue();

    $account->update(['status' => AccountStatus::INVITED]);

    expect($account->isJoined())
        ->toBeFalse();
});

test('account can check for pending identity transaction', function () {

    foreach (IdentificationStatus::cases() as $status) {
        $account = Account::factory()->create();
        IdentityTransaction::factory()->create(['account_id' => $account->id, 'status' => $status]);

        if ($status === IdentificationStatus::PENDING) {
            expect($account->hasPendingIdentificationRequest())
                ->toBeTrue();
        } else {
            expect($account->hasPendingIdentificationRequest())
                ->toBeFalse();
        }
    }
});

test('account can get pending identity transaction', function () {
    foreach (IdentificationStatus::cases() as $status) {
        $account = Account::factory()->create();
        IdentityTransaction::factory()->create(['account_id' => $account->id, 'status' => $status]);

        if ($status === IdentificationStatus::PENDING) {
            expect($account->getPendingIdentityTransaction())
                ->toBeInstanceOf(IdentityTransaction::class);
        } else {
            expect($account->getPendingIdentityTransaction())
                ->toBeNull();
        }
    }
});

test('account contract can be resolved to model class', function () {
    $account = app(\AmaizingCompany\CertifactionClient\Contracts\Account::class);

    expect($account)
        ->toBeInstanceOf(Account::class);
});
