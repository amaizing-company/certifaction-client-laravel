<?php

use AmaizingCompany\CertifactionClient\Api\Requests\RevokeDocumentRequest;

pest()->group('api');

beforeEach(function () {
    $this->revokeDocumentRequest = new RevokeDocumentRequest($this->getPdfFileContents());
});

it('can initiate instance', function () {
    expect($this->revokeDocumentRequest)
        ->toBeInstanceOf(RevokeDocumentRequest::class);
});

it('can handle file', function () {
    expect($this->revokeDocumentRequest->getFileContents())
        ->toBeString()
        ->toBe($this->getPdfFileContents())
        ->and($this->revokeDocumentRequest->getFileContents(true))
        ->toBeString()
        ->toBe(base64_encode($this->getPdfFileContents()));
});
