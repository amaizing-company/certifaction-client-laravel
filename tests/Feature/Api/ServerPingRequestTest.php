<?php

use AmaizingCompany\CertifactionClient\Api\Requests\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Requests\ServerPingRequest;

pest()->group('api');

it('can initiate instance', function () {
    expect(ServerPingRequest::make())
        ->toBeInstanceOf(Request::class)
        ->toBeInstanceOf(ServerPingRequest::class);
});
