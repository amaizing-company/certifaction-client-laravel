<?php

use AmaizingCompany\CertifactionClient\Api\Requests\CancelSignatureRequest;
use AmaizingCompany\CertifactionClient\Api\Signer;
use Illuminate\Http\Client\RequestException;

pest()->group('api');

beforeEach(function () {
    $this->cancelSignatureRequest = new CancelSignatureRequest($this->getPdfFileContents());
});

it('can initiate object', function () {
    expect($this->cancelSignatureRequest)
        ->toBeInstanceOf(CancelSignatureRequest::class);
});

it('can handle file', function () {
    expect($this->cancelSignatureRequest->getFileContents())
        ->toBeString()
        ->toBe($this->getPdfFileContents())
        ->and($this->cancelSignatureRequest->getFileContents(true))
        ->toBeString()
        ->toBe(base64_encode($this->getPdfFileContents()));
});

it('can handle note', function () {
    expect($this->cancelSignatureRequest->getNote())
        ->toBeNull()
        ->and($this->cancelSignatureRequest->note('TEST')->getNote())
        ->toBeString()
        ->toBe('TEST');

});

it('can handle query params', function () {
    expect($this->cancelSignatureRequest->getQueryParams())
        ->toBeArray()
        ->toBeEmpty()
        ->and($this->cancelSignatureRequest->note('TEST')->getQueryParams())
        ->toHaveCount(1)
        ->toHaveKey('note');
});

it('can handle signer', function () {
    expect($this->cancelSignatureRequest->for(new Signer('test@example.com'))->getQueryParams())
        ->toBeArray()
        ->toHaveCount(1)
        ->toHaveKey('email');
});

it('can get signer', function () {
    expect($this->cancelSignatureRequest->getSigner())
        ->toBeNull()
        ->and($this->cancelSignatureRequest->for(new Signer('test@example.com'))->getSigner())
        ->toBeInstanceOf(Signer::class);
});

it('can get correct signer params', function () {
    expect($this->cancelSignatureRequest->getSignerParams())
        ->toBeArray()
        ->toMatchArray(['email']);
});

it('can get endpoint depending on signer is given', function () {
    expect($this->cancelSignatureRequest->getEndpoint())
        ->toBeString()
        ->toBe($this->cancelSignatureRequest::ENDPOINT_ALL_REQUESTS)
        ->and($this->cancelSignatureRequest->for(new Signer('test@example.com'))->getEndpoint())
        ->toBe($this->cancelSignatureRequest::ENDPOINT_SIGNERS_REQUEST);
});
