<?php

use AmaizingCompany\CertifactionClient\Enums\SignatureTransactionStatus;
use AmaizingCompany\CertifactionClient\Models\Document;
use AmaizingCompany\CertifactionClient\Models\SignatureTransaction;
use AmaizingCompany\CertifactionClient\Tests\Models\File;
use AmaizingCompany\CertifactionClient\Tests\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

pest()->group('package', 'models');

uses(RefreshDatabase::class);

test('signature transaction model can be initiated', function () {
    $transaction = SignatureTransaction::factory()->create();

    expect($transaction)
        ->toBeInstanceOf(SignatureTransaction::class);
});

test('signature transaction can be related to signer', function () {
    $transaction = SignatureTransaction::factory()->create();

    expect($transaction->signer()->first())
        ->toBeInstanceOf(User::class);
});

test('signature transaction can be related to documents', function () {
    $transaction = SignatureTransaction::factory()->create();
    $document = Document::factory()->create();

    $transaction->documents()->attach($document);

    expect($transaction->documents()->first())
        ->toBeInstanceOf(Document::class);
});

test('signature transaction can get webhook url', function () {
    $transaction = SignatureTransaction::factory()->create();
    $document = Document::factory()->create();
    $transaction->documents()->attach($document);

    expect($transaction->getWebhookUrl())
        ->toBeString()
        ->and(Crypt::decrypt(Str::replace(app(File::class)->getWebhookUrl().'/', '', $transaction->getWebhookUrl())))
        ->toBe($transaction->id);
});

test('signature transaction can mark as pending', function () {
    $transaction = SignatureTransaction::factory()->create([
        'status' => SignatureTransactionStatus::INTENDED,
        'request_url' => null,
        'requested_at' => null,
    ]);

    $requestedAt = Carbon::now();

    expect($transaction->markPending('https://test.example', $requestedAt))
        ->toBeTrue()
        ->and($transaction->status)
        ->toBe(SignatureTransactionStatus::PENDING)
        ->and($transaction->request_url)
        ->toBeString()
        ->toBe('https://test.example')
        ->and($transaction->requested_at->format('Y-m-d H:i:s'))
        ->toBe($requestedAt->format('Y-m-d H:i:s'));
});

test('signature transaction can mark as finished', function () {
    $transaction = SignatureTransaction::factory()->create([
        'finished_at' => null,
        'status' => SignatureTransactionStatus::PENDING,
    ]);

    $finishedAt = Carbon::now();

    expect($transaction->markFinished($finishedAt))
        ->toBeTrue()
        ->and($transaction->status)
        ->toBe(SignatureTransactionStatus::SUCCEED)
        ->and($transaction->finished_at->format('Y-m-d H:i:s'))
        ->toBe($finishedAt->format('Y-m-d H:i:s'));
});

test('signature transaction can mark as failed', function () {
    $transaction = SignatureTransaction::factory()->create([
        'status' => SignatureTransactionStatus::PENDING,
        'finished_at' => null,
        'failure_reason' => null,
    ]);

    $finishedAt = Carbon::now();

    expect($transaction->markFailed('test fail', $finishedAt))
        ->toBeTrue()
        ->and($transaction->status)
        ->toBe(SignatureTransactionStatus::FAILED)
        ->and($transaction->finished_at->format('Y-m-d H:i:s'))
        ->toBe($finishedAt->format('Y-m-d H:i:s'))
        ->and($transaction->failure_reason)
        ->toBeString()
        ->toBe('test fail');
});

test('signature transaction contract can be resolved to model class', function () {
    $account = app(\AmaizingCompany\CertifactionClient\Contracts\SignatureTransaction::class);

    expect($account)
        ->toBeInstanceOf(SignatureTransaction::class);
});
