<?php

use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Requests\CheckServerHealthRequest;

pest()->group('api', 'requests');

it('can initiate instance', function () {
    expect(CheckServerHealthRequest::make())
        ->toBeInstanceOf(Request::class)
        ->toBeInstanceOf(CheckServerHealthRequest::class);
});
