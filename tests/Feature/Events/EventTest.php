<?php

use AmaizingCompany\CertifactionClient\Api\Requests\PrepareDocumentRequest;
use AmaizingCompany\CertifactionClient\Enums\DocumentPrepareScope;
use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
use AmaizingCompany\CertifactionClient\Events\AccountDeleted;
use AmaizingCompany\CertifactionClient\Events\DocumentPreparationFailed;
use AmaizingCompany\CertifactionClient\Events\DocumentPrepared;
use AmaizingCompany\CertifactionClient\Events\FileTransactionFailed;
use AmaizingCompany\CertifactionClient\Events\FileTransactionFinished;
use AmaizingCompany\CertifactionClient\Events\FileTransactionStarted;
use AmaizingCompany\CertifactionClient\Events\IdentificationRequestFinished;
use AmaizingCompany\CertifactionClient\Events\IdentificationRequestStarted;
use AmaizingCompany\CertifactionClient\Events\IdentificationStatusCheckFinished;
use AmaizingCompany\CertifactionClient\Events\SignatureRequestFailed;
use AmaizingCompany\CertifactionClient\Events\SignatureRequestFinished;
use AmaizingCompany\CertifactionClient\Events\SignatureRequestStarted;
use AmaizingCompany\CertifactionClient\Events\UserInvitedToCertifaction;
use AmaizingCompany\CertifactionClient\Events\UserJoinedCertifaction;
use AmaizingCompany\CertifactionClient\Models\Account;
use AmaizingCompany\CertifactionClient\Models\Document;
use AmaizingCompany\CertifactionClient\Models\FileTransaction;
use AmaizingCompany\CertifactionClient\Models\IdentityTransaction;
use AmaizingCompany\CertifactionClient\Models\SignatureTransaction;
use AmaizingCompany\CertifactionClient\Tests\Models\File;
use AmaizingCompany\CertifactionClient\Tests\Models\User;

pest()->group('package', 'events');

test('account deleted contract can be resolved to an event', function () {
    $instance = app(\AmaizingCompany\CertifactionClient\Contracts\Events\AccountDeleted::class, ['user' => new User()]);

    expect($instance)
        ->toBeInstanceOf(AccountDeleted::class);
});

test('document preparation failed contract can be resolved to an event', function () {
    $instance = app(
        \AmaizingCompany\CertifactionClient\Contracts\Events\DocumentPreparationFailed::class,
        [
            'request' => new PrepareDocumentRequest('test', DocumentPrepareScope::SIGN),
            'signable' => new File()
        ]
    );

    expect($instance)
        ->toBeInstanceOf(DocumentPreparationFailed::class);
});

test('document prepared contract can be resolved to an event', function () {
    $instance = app(
        \AmaizingCompany\CertifactionClient\Contracts\Events\DocumentPrepared::class,
        [
            'document' => new Document()
        ]
    );

    expect($instance)
        ->toBeInstanceOf(DocumentPrepared::class);
});

test('file transaction failed contract can be resolved to an event', function () {
    $instance = app(
        \AmaizingCompany\CertifactionClient\Contracts\Events\FileTransactionFailed::class,
        [
            'transaction' => new FileTransaction()
        ]
    );

    expect($instance)
        ->toBeInstanceOf(FileTransactionFailed::class);
});

test('file transaction finished contract can be resolved to an event', function () {
    $instance = app(
        \AmaizingCompany\CertifactionClient\Contracts\Events\FileTransactionFinished::class,
        [
            'transaction' => new FileTransaction()
        ]
    );

    expect($instance)
        ->toBeInstanceOf(FileTransactionFinished::class);
});

test('file transaction started contract can be resolved to an event', function () {
    $instance = app(
        \AmaizingCompany\CertifactionClient\Contracts\Events\FileTransactionStarted::class,
        [
            'transaction' => new FileTransaction()
        ]
    );

    expect($instance)
        ->toBeInstanceOf(FileTransactionStarted::class);
});

test('identification request finished contract can be resolved to an event', function () {
    $instance = app(
        \AmaizingCompany\CertifactionClient\Contracts\Events\IdentificationRequestFinished::class,
        [
            'identityTransaction' => new IdentityTransaction(),
            'status' => IdentificationStatus::VERIFIED,
        ]
    );

    expect($instance)
        ->toBeInstanceOf(IdentificationRequestFinished::class);
});

test('identification request started contract can be resolved to an event', function () {
    $instance = app(
        \AmaizingCompany\CertifactionClient\Contracts\Events\IdentificationRequestStarted::class,
        [
            'identityTransaction' => new IdentityTransaction()
        ]
    );

    expect($instance)
        ->toBeInstanceOf(IdentificationRequestStarted::class);
});

test('identification status check finished contract can be resolved to an event', function () {
    $instance = app(
        \AmaizingCompany\CertifactionClient\Contracts\Events\IdentificationStatusCheckFinished::class,
        [
            'identityTransaction' => new IdentityTransaction()
        ]
    );

    expect($instance)
        ->toBeInstanceOf(IdentificationStatusCheckFinished::class);
});

test('signature request failed contract can be resolved to an event', function () {
    $instance = app(
        \AmaizingCompany\CertifactionClient\Contracts\Events\SignatureRequestFailed::class,
        [
            'transaction' => new SignatureTransaction()
        ]
    );

    expect($instance)
        ->toBeInstanceOf(SignatureRequestFailed::class);
});

test('signature request finished contract can be resolved to an event', function () {
    $instance = app(
        \AmaizingCompany\CertifactionClient\Contracts\Events\SignatureRequestFinished::class,
        [
            'transaction' => new SignatureTransaction()
        ]
    );

    expect($instance)
        ->toBeInstanceOf(SignatureRequestFinished::class);
});

test('signature request started contract can be resolved to an event', function () {
    $instance = app(
        \AmaizingCompany\CertifactionClient\Contracts\Events\SignatureRequestStarted::class,
        [
            'transaction' => new SignatureTransaction()
        ]
    );

    expect($instance)
        ->toBeInstanceOf(SignatureRequestStarted::class);
});

test('user invited to certifaction contract can be resolved to an event', function () {
    $instance = app(
        \AmaizingCompany\CertifactionClient\Contracts\Events\UserInvitedToCertifaction::class,
        [
            'user' => new User(),
            'roleId' => 'test',
        ]
    );

    expect($instance)
        ->toBeInstanceOf(UserInvitedToCertifaction::class);
});

test('user joined certifaction contract can be resolved to an event', function () {
    $instance = app(
        \AmaizingCompany\CertifactionClient\Contracts\Events\UserJoinedCertifaction::class,
        [
            'account' => new Account(),
        ]
    );

    expect($instance)
        ->toBeInstanceOf(UserJoinedCertifaction::class);
});
