<?php

use AmaizingCompany\CertifactionClient\Api\Responses\CheckServerHealthResponse;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;

it ('can initiate instance', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse)
        ->toBeInstanceOf(CheckServerHealthResponse::class);

})->with([
    [200, [], [
        [
            'description' => 'description',
            'service_name' => 'database',
            'status' => 'UP',
        ]
    ]],
    [500, [], []]
]);

it ('can get services', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse)
        ->when($serverHealthResponse->successful(), fn ($expectation) => $expectation->getServices()->toBeInstanceOf(Collection::class)->toHaveCount(1))
        ->when($serverHealthResponse->successful() === false, fn ($expectation) => $expectation->getServices()->toBeInstanceOf(Collection::class)->toBeEmpty());

})->with([
    [200, [], [
        [
            'description' => 'description',
            'service_name' => 'database',
            'status' => 'UP',
        ]
    ]],
    [500, [], []]
]);

it ('can get healthy services when all services healthy', function () {
    $body = [
        ['description' => 'description', 'service_name' => 'database', 'status' => 'UP'],
        ['description' => 'description', 'service_name' => 'ethereum', 'status' => 'UP'],
    ];

    $response = new Response(200, [], json_encode($body));
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse->getHealthyServices())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(2);
});

it ('can get healthy services when some service unhealthy', function () {
    $body = [
        ['description' => 'description', 'service_name' => 'database', 'status' => 'UP'],
        ['description' => 'description', 'service_name' => 'ethereum', 'status' => 'DOWN'],
    ];

    $response = new Response(200, [], json_encode($body));
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse->getHealthyServices())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(1);
});

it ('can get healthy services when all services are unhealthy', function () {
    $body = [
        ['description' => 'description', 'service_name' => 'database', 'status' => 'DOWN'],
        ['description' => 'description', 'service_name' => 'ethereum', 'status' => 'DOWN'],
    ];

    $response = new Response(200, [], json_encode($body));
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse->getHealthyServices())
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});

it ('can get healthy services on bad response', function () {
    $response = new Response(500, [], null);
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse->getHealthyServices())
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});

it ('can get unhealthy services when all services are unhealthy', function () {
    $body = [
        ['description' => 'description', 'service_name' => 'database', 'status' => 'DOWN'],
        ['description' => 'description', 'service_name' => 'ethereum', 'status' => 'DOWN'],
    ];

    $response = new Response(200, [], json_encode($body));
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse->getUnhealthyServices())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(2);
});

it ('can get unhealthy services when some services are healthy', function () {
    $body = [
        ['description' => 'description', 'service_name' => 'database', 'status' => 'UP'],
        ['description' => 'description', 'service_name' => 'ethereum', 'status' => 'DOWN'],
    ];

    $response = new Response(200, [], json_encode($body));
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse->getUnhealthyServices())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(1);
});

it ('can get unhealthy services when all services are healthy', function () {
    $body = [
        ['description' => 'description', 'service_name' => 'database', 'status' => 'UP'],
        ['description' => 'description', 'service_name' => 'ethereum', 'status' => 'UP'],
    ];

    $response = new Response(200, [], json_encode($body));
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse->getUnhealthyServices())
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});

it ('can get unhealthy services on bad response', function () {
    $response = new Response(500, [], null);
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse->getUnhealthyServices())
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});

it ('can check if all services are healthy when all services are healthy', function () {
    $body = [
        ['description' => 'description', 'service_name' => 'database', 'status' => 'UP'],
        ['description' => 'description', 'service_name' => 'ethereum', 'status' => 'UP'],
    ];

    $response = new Response(200, [], json_encode($body));
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse->allServicesHealthy())
        ->toBeBool()
        ->toBeTrue();
});

it ('can check if all services are healthy when some services are unhealthy', function () {
    $body = [
        ['description' => 'description', 'service_name' => 'database', 'status' => 'UP'],
        ['description' => 'description', 'service_name' => 'ethereum', 'status' => 'DOWN'],
    ];

    $response = new Response(200, [], json_encode($body));
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse->allServicesHealthy())
        ->toBeBool()
        ->toBeFalse();
});

it ('can check if all services are healthy when all services are unhealthy', function () {
    $body = [
        ['description' => 'description', 'service_name' => 'database', 'status' => 'DOWN'],
        ['description' => 'description', 'service_name' => 'ethereum', 'status' => 'DOWN'],
    ];

    $response = new Response(200, [], json_encode($body));
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse->allServicesHealthy())
        ->toBeBool()
        ->toBeFalse();
});

it ('can check if all services are healthy on bad response', function () {
    $response = new Response(500, [], null);
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse->allServicesHealthy())
        ->toBeBool()
        ->toBeFalse();
});

it ('can check if some services are unhealthy when all services are unhealthy', function () {
    $body = [
        ['description' => 'description', 'service_name' => 'database', 'status' => 'DOWN'],
        ['description' => 'description', 'service_name' => 'ethereum', 'status' => 'DOWN'],
    ];

    $response = new Response(200, [], json_encode($body));
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse->hasUnhealthyServices())
        ->toBeBool()
        ->toBeTrue();
});

it ('can check if some services are unhealthy when all services are healthy', function () {
    $body = [
        ['description' => 'description', 'service_name' => 'database', 'status' => 'UP'],
        ['description' => 'description', 'service_name' => 'ethereum', 'status' => 'UP'],
    ];

    $response = new Response(200, [], json_encode($body));
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse->hasUnhealthyServices())
        ->toBeBool()
        ->toBeFalse();
});

it ('can check if some services are unhealthy when some services are healthy', function () {
    $body = [
        ['description' => 'description', 'service_name' => 'database', 'status' => 'UP'],
        ['description' => 'description', 'service_name' => 'ethereum', 'status' => 'DOWN'],
    ];

    $response = new Response(200, [], json_encode($body));
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse->hasUnhealthyServices())
        ->toBeBool()
        ->toBeTrue();
});

it ('can check if some services are unhealthy on bad response', function () {
    $response = new Response(500, [], null);
    $serverHealthResponse = new CheckServerHealthResponse($response);

    expect($serverHealthResponse->hasUnhealthyServices())
        ->toBeBool()
        ->toBeFalse();
});
