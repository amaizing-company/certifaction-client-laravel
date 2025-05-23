<?php

use AmaizingCompany\CertifactionClient\Api\DataObjects\DocumentItem;

pest()->group('api', 'data-objects');

beforeEach(function () {
    $this->documentItem = new DocumentItem('https://test.example', 'Test');
});

it('can initiate instance', function () {
    expect($this->documentItem)
        ->toBeInstanceOf(DocumentItem::class);
});

it('can get url', function () {
    expect($this->documentItem->getUrl())
        ->toBeString()
        ->toBe('https://test.example');
});

it('can get name', function () {
    expect($this->documentItem->getName())
        ->toBeString()
        ->toBe('Test');
});
