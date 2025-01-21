<?php

use AmaizingCompany\CertifactionClient\Api\Requests\CheckServerHealthRequest;
use AmaizingCompany\CertifactionClient\Api\Requests\Contracts\Request;

pest()->group('api');

it('can initiate instance', function () {
    expect(CheckServerHealthRequest::make())
        ->toBeInstanceOf(Request::class)
        ->toBeInstanceOf(CheckServerHealthRequest::class);
});
