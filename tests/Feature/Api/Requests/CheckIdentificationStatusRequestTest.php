<?php

use AmaizingCompany\CertifactionClient\Api\Requests\CheckIdentificationStatusRequest;

pest()->group('api', 'requests');

it('can initiate instance', function () {
    $request = new CheckIdentificationStatusRequest('TEST_ID');

    expect($request)
        ->toBeInstanceOf(CheckIdentificationStatusRequest::class);
});

it('can get identification id', function () {
    $request = new CheckIdentificationStatusRequest('TEST_ID');

    expect($request->getIdentificationId())
        ->toBeString()
        ->toBe('TEST_ID');
});

it('can set new identification id', function () {
    $request = new CheckIdentificationStatusRequest('TEST_ID');

    expect($request->identificationId('MODIFIED_TEST_ID')->getIdentificationId())
        ->toBe('MODIFIED_TEST_ID');
});
