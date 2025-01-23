<?php

use AmaizingCompany\CertifactionClient\Api\Requests\DeleteDocumentAccessRequest;

pest()->group('api', 'requests');

beforeEach(function () {
    $this->deleteDocumentAccessRequest = new DeleteDocumentAccessRequest($this->getPdfFileContents());
});

it('can initiate instance', function () {
    expect($this->deleteDocumentAccessRequest)
        ->toBeInstanceOf(DeleteDocumentAccessRequest::class);
});

it('can handle file', function () {
    expect($this->deleteDocumentAccessRequest->getFileContents())
        ->toBeString()
        ->toBe($this->getPdfFileContents())
        ->and($this->deleteDocumentAccessRequest->getFileContents(true))
        ->toBeString()
        ->toBe(base64_encode($this->getPdfFileContents()));
});
