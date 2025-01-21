<?php

use AmaizingCompany\CertifactionClient\Api\Responses\ServerPingResponse;
use GuzzleHttp\Psr7\Response;

pest()->group('api');

it('can initiate instance', function (int $status, array $headers) {
    $response = new Response($status, $headers, null);
    $serverPingResponse = new ServerPingResponse($response);

    expect($serverPingResponse)
        ->toBeInstanceOf(ServerPingResponse::class);
})->with([
    [200, []],
    [500, []],
]);

it('can check if server is alive', function (int $status, array $headers) {
    $response = new Response($status, $headers, null);
    $serverPingResponse = new ServerPingResponse($response);

    expect($serverPingResponse)
        ->when($serverPingResponse->successful(), fn ($expectation) => $expectation->isServerAlive()->toBeBool()->toBeTrue())
        ->when($serverPingResponse->successful() === false, fn ($expectation) => $expectation->isServerAlive()->toBeBool()->toBeFalse());
})->with([
    [200, []],
    [500, []],
]);
