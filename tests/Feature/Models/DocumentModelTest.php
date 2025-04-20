<?php

use AmaizingCompany\CertifactionClient\Contracts\Signable;
use AmaizingCompany\CertifactionClient\Enums\DocumentStatus;
use AmaizingCompany\CertifactionClient\Models\Document;
use AmaizingCompany\CertifactionClient\Models\FileTransaction;
use AmaizingCompany\CertifactionClient\Models\SignatureTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

pest()->group('package', 'models');

uses(RefreshDatabase::class);

test('document model can be initiated', function () {
    $document = Document::factory()->create();

    expect($document)
        ->toBeInstanceOf(Document::class);
});

test('document model can get storage path from signable', function () {
    $document = Document::factory()->create();
    $storage = Storage::fake('local');

    expect($document->getStoragePath())
        ->toBeString()
        ->toBe($storage->path($document->signable()->first()->getDocumentName()))
        ->and(Str::endsWith($document->getStoragePath(), '.pdf'))
        ->toBeTrue();
});

test('document model can get storage disk', function () {
    $document = Document::factory()->create();

    expect($document->getStorageDisk())
        ->toBeString()
        ->toBe('local');
});

test('document can relate to signable', function () {
    $document = Document::factory()->create();

    expect($document->signable()->first())
        ->toBeInstanceOf(Signable::class);
});

test('document can relate to signature transaction', function () {
    $document = Document::factory()->create();
    $signatureTransaction = SignatureTransaction::factory()->create();
    $signatureTransaction->documents()->attach($document);

    expect($document->signatureTransactions()->first())
        ->toBeInstanceOf(SignatureTransaction::class);
});

test('document can relate to file transaction', function () {
    $document = Document::factory()->create();
    FileTransaction::factory()->create(['document_id' => $document->id]);

    expect($document->fileTransactions()->first())
        ->toBeInstanceOf(FileTransaction::class);
});

it('can check document is intent', function () {
    foreach (DocumentStatus::cases() as $status) {
        $document = Document::factory()->create(['status' => $status]);

        if ($status === DocumentStatus::INTENT) {
            expect($document->isIntent())->toBeTrue();
        } else {
            expect($document->isIntent())->toBeFalse();
        }
    }
});

it('can check document is prepared', function () {
    foreach (DocumentStatus::cases() as $status) {
        $document = Document::factory()->create(['status' => $status]);

        if ($status === DocumentStatus::PREPARED) {
            expect($document->isPrepared())->toBeTrue();
        } else {
            expect($document->isPrepared())->toBeFalse();
        }
    }
});

it('can check document is signed', function () {
    foreach (DocumentStatus::cases() as $status) {
        $document = Document::factory()->create(['status' => $status]);

        if ($status === DocumentStatus::SIGNED) {
            expect($document->isSigned())->toBeTrue();
        } else {
            expect($document->isSigned())->toBeFalse();
        }
    }
});

it('can check document is signature failed', function () {
    foreach (DocumentStatus::cases() as $status) {
        $document = Document::factory()->create(['status' => $status]);

        if ($status === DocumentStatus::SIGNATURE_FAILED) {
            expect($document->isSignatureFailed())->toBeTrue();
        } else {
            expect($document->isSignatureFailed())->toBeFalse();
        }
    }
});

test('document contract can be resolved to model class', function () {
    $account = app(\AmaizingCompany\CertifactionClient\Contracts\Document::class);

    expect($account)
        ->toBeInstanceOf(Document::class);
});
