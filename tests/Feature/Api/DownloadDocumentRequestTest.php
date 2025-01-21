<?php

use AmaizingCompany\CertifactionClient\Api\Requests\DownloadDocumentRequest;

pest()->group('api');

beforeEach(function () {
    $this->downloadDocumentRequest = new DownloadDocumentRequest('https://test.example');
});

it('can initiate instance', function () {
    expect($this->downloadDocumentRequest)
        ->toBeInstanceOf(DownloadDocumentRequest::class);
});

it('can get file url', function () {
    expect($this->downloadDocumentRequest->getFileUrl())
        ->toBeString()
        ->toBe('https://test.example');
});

it('can get query params', function () {
    expect($this->downloadDocumentRequest->getQueryParams())
        ->toBeArray()
        ->toHaveKey('file');
});

it('can handle password', function () {
    expect($this->downloadDocumentRequest->getPassword())
        ->toBeNull()
        ->and($this->downloadDocumentRequest->password('TEST')->getPassword())
        ->toBe('TEST')
        ->toBeString();
});
