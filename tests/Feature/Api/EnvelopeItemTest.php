<?php

use AmaizingCompany\CertifactionClient\Api\EnvelopeItem;
use AmaizingCompany\CertifactionClient\Enums\DocumentStatus;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;
use Illuminate\Support\Carbon;

pest()->group('api');

it('can initiate instance with empty data', function () {
    expect(new EnvelopeItem([]))
        ->toBeInstanceOf(EnvelopeItem::class);
});

it('can initiate instance from data', function ($jurisdiction, $status, $signedAt, $comments) {
    expect(new EnvelopeItem([
        'legal_weight' => 'standard',
        'jurisdiction' => $jurisdiction,
        'status' => $status,
        'signed_at' => $signedAt,
        'file_url' => 'https://example.test',
        'file_id' => 'FILE_ID',
        'comments' => $comments,
    ]))->toBeInstanceOf(EnvelopeItem::class);
})->with([
    ['', '', '', []],
    ['eIDAS', 'signed', '01.01.2025', ['testcomment']],
]);

it ('can get legal weight', function () {
    $item = new EnvelopeItem(['legal_weight' => 'standard']);

    expect($item->getLegalWeight())
         ->toBeString()
         ->toBe('standard');
});

it ('can get jurisdiction', function () {
    $item = new EnvelopeItem(['jurisdiction' => 'eIDAS']);

    expect($item->getJurisdiction())
        ->toBeInstanceOf(Jurisdiction::class)
        ->toBe(Jurisdiction::EIDAS);
});

it ('can get status', function () {
    $item = new EnvelopeItem(['status' => 'signed']);

    expect($item->getStatus())
        ->toBeInstanceOf(DocumentStatus::class)
        ->toBe(DocumentStatus::SIGNED);
});

it ('can get signed at', function () {
    $item = new EnvelopeItem(['signed_at' => '01.01.2025']);

    expect($item->getSignedAt())
        ->toBeInstanceOf(Carbon::class)
        ->and($item->getSignedAt()->format('d.m.Y'))
        ->toBe('01.01.2025');
});

it ('can get file url', function () {
    $item = new EnvelopeItem(['file_url' => 'https://example.test']);

    expect($item->getFileUrl())
        ->toBeString()
        ->toBe('https://example.test');
});

it ('can get file id', function () {
    $item = new EnvelopeItem(['file_id' => 'FILE_ID']);

    expect($item->getFileId())
        ->toBeString()
        ->toBe('FILE_ID');
});

it ('can get comments', function () {
    $item = new EnvelopeItem(['comments' => []]);

    expect($item->getComments())
        ->toBeArray()
        ->toBeEmpty();
});
