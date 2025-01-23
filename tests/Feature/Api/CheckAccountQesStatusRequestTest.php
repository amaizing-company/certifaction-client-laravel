<?php

use AmaizingCompany\CertifactionClient\Api\Requests\CheckAccountQesStatusRequest;

pest()->group('api');

beforeEach(function () {
    $this->checkAccountStatusRequest = new CheckAccountQesStatusRequest('+49123456789');
});

it('can initiate instance', function () {
    expect($this->checkAccountStatusRequest)
        ->toBeInstanceOf(CheckAccountQesStatusRequest::class);
});

it('can handle mobile number', function () {
    expect($this->checkAccountStatusRequest->getMobileNumber())
        ->toBeString()
        ->toBe('49123456789')
        ->and($this->checkAccountStatusRequest->mobileNumber('41123456789')->getMobileNumber())
        ->toBeString()
        ->toBe('41123456789');
});
