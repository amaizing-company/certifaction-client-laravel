<?php

use AmaizingCompany\CertifactionClient\Api\DataObjects\DocumentItem;
use AmaizingCompany\CertifactionClient\Api\Requests\AddDocumentsToSignatureRequest;
use Illuminate\Support\Collection;

pest()->group('api', 'requests');

beforeEach(function () {
    $this->addDocumentsToSignatureRequest = new AddDocumentsToSignatureRequest('https://test.example');
});

it('can initiate instance', function () {
    expect($this->addDocumentsToSignatureRequest)
        ->toBeInstanceOf(AddDocumentsToSignatureRequest::class);
});

it('can get query params', function () {
    expect($this->addDocumentsToSignatureRequest->getQueryParams())
        ->toBeArray()
        ->toHaveCount(1);
});

it('can get request url', function () {
    expect($this->addDocumentsToSignatureRequest->getRequestUrl(false))
        ->toBeString()
        ->toBe('https://test.example')
        ->and($this->addDocumentsToSignatureRequest->getRequestUrl())
        ->toBeString()
        ->toBe(urlencode('https://test.example'));
});

it('can handle documents', function () {
    expect($this->addDocumentsToSignatureRequest->getDocuments())
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty()
        ->and($this->addDocumentsToSignatureRequest->addDocument(new DocumentItem('https://test.example', 'test.pdf'))->getDocuments())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(1)
        ->and($this->addDocumentsToSignatureRequest->addDocuments(
            new DocumentItem('https://test.example', 'test.pdf'),
            new DocumentItem('https://test.example', 'test.pdf')
        )->getDocuments())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(3);
});
