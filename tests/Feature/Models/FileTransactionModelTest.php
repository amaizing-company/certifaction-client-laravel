<?php

use AmaizingCompany\CertifactionClient\Enums\FileTransactionStatus;
use AmaizingCompany\CertifactionClient\Models\Document;
use AmaizingCompany\CertifactionClient\Models\FileTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test ('file transaction model can be initiated', function () {
    $transaction = FileTransaction::factory()->create();

    expect($transaction)
        ->toBeInstanceOf(FileTransaction::class);
});

test ('file transaction can relate to document', function () {
    $transaction = FileTransaction::factory()->for(Document::factory())->create();

    expect($transaction->document()->first())
        ->toBeInstanceOf(Document::class);
});

test ('file transaction can marked as failed', function () {
    $transaction = FileTransaction::factory()->create(['status' => FileTransactionStatus::PENDING]);

    expect($transaction->markFailure('test fail'))
        ->toBeTrue()
        ->and($transaction->status)
        ->toBe(FileTransactionStatus::FAILURE)
        ->and($transaction->failure_reason)
        ->toBeString()
        ->toBe('test fail')
        ->and($transaction->finished_at)
        ->not->toBeNull()
        ->and(FileTransaction::query()->where('original_transaction_id', $transaction->id)->exists())
        ->toBeTrue();
});

test ('file transaction can marked as failed without replication', function () {
    $transaction = FileTransaction::factory()->create(['status' => FileTransactionStatus::PENDING]);

    expect($transaction->markFailure('test fail', replicate: false))
        ->toBeTrue()
        ->and(FileTransaction::query()->where('original_transaction_id', $transaction->id)->exists())
        ->toBeFalse();
});

test ('file transaction can relate to original transaction', function () {
    $transaction = FileTransaction::factory()->create(['status' => FileTransactionStatus::PENDING]);
    $transaction->markFailure('test fail');

    $childTransaction = FileTransaction::query()->where('original_transaction_id', $transaction->id)->first();

    expect($childTransaction->originalTransaction()->first())
        ->toBeInstanceOf(FileTransaction::class);
});

test ('file transaction can relate to child transactions', function () {
    $transaction = FileTransaction::factory()->create(['status' => FileTransactionStatus::PENDING]);
    $transaction->markFailure('test fail');

    expect($transaction->childTransactions()->first())
        ->toBeInstanceOf(FileTransaction::class);
});

test ('file transaction can check if has parent transaction', function () {
    $transaction = FileTransaction::factory()->create(['status' => FileTransactionStatus::PENDING]);
    $transaction->markFailure('test fail');
    $childTransaction = FileTransaction::query()->where('original_transaction_id', $transaction->id)->first();

    expect($transaction->hasParent())
        ->toBeFalse()
        ->and($childTransaction->hasParent())
        ->toBeTrue();
});

test ('file transaction can check if has child transactions', function () {
    $transaction = FileTransaction::factory()->create(['status' => FileTransactionStatus::PENDING]);

    expect($transaction->hasChildren())
        ->toBeFalse();

    $transaction->markFailure('test fail');

    expect($transaction->hasChildren())
        ->toBeTrue();
});

test ('file transaction can mark as pending', function () {
    $transaction = FileTransaction::factory()->create(['status' => FileTransactionStatus::INTENT]);

    expect($transaction->markPending())
        ->toBeTrue()
        ->and($transaction->status)
        ->toBe(FileTransactionStatus::PENDING)
        ->and($transaction->requested_at)
        ->not->toBeNull();
});

test ('file transaction can mark as succeeded', function () {
    $transaction = FileTransaction::factory()->create(['status' => FileTransactionStatus::PENDING]);

    expect($transaction->markSuccess())
        ->toBeTrue()
        ->and($transaction->status)
        ->toBe(FileTransactionStatus::SUCCESS)
        ->and($transaction->finished_at)
        ->not->toBeNull();
});
