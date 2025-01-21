<?php

use AmaizingCompany\CertifactionClient\Api\Requests\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Requests\ServerPingRequest;
use AmaizingCompany\CertifactionClient\Api\Responses\Contracts\CertifactionResponse;
use AmaizingCompany\CertifactionClient\Api\Responses\ServerPingResponse;

pest()->group('api');

it('can initiate instance', function () {
    expect(ServerPingRequest::make())
        ->toBeInstanceOf(Request::class)
        ->toBeInstanceOf(ServerPingRequest::class);
});
