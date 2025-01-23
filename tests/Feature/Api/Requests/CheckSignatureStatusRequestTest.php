<?php

use AmaizingCompany\CertifactionClient\Api\Requests\CheckSignatureStatusRequest;

pest()->group('api', 'requests');

beforeEach(function () {
    $this->checkSignatureStatusRequest = new CheckSignatureStatusRequest('https://test.example');
});

it('can initiate instance', function () {
    expect($this->checkSignatureStatusRequest)
        ->toBeInstanceOf(CheckSignatureStatusRequest::class);
});

it('can get request url', function () {
    expect($this->checkSignatureStatusRequest->getRequestUrl(false))
        ->toBeString()
        ->toBe('https://test.example')
        ->and($this->checkSignatureStatusRequest->getRequestUrl())
        ->toBeString()
        ->toBe(urlencode('https://test.example'));
});

it('can get query params', function () {
    expect($this->checkSignatureStatusRequest->getQueryParams())
        ->toBeArray()
        ->toHaveKey('request_url');
});
