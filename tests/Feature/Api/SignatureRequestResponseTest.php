<?php

use AmaizingCompany\CertifactionClient\Api\Responses\SignatureRequestResponse;
use GuzzleHttp\Psr7\Response;

pest()->group('api');

it('can initiate instance', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));
    $signatureRequestResponse = new SignatureRequestResponse($response);

    expect($signatureRequestResponse)
        ->toBeInstanceOf(SignatureRequestResponse::class);

})->with([
    [200, [], ['request_url' => 'https://test.example']],
    [500, [], []],
]);

it('can get request url', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));
    $signatureRequestResponse = new SignatureRequestResponse($response);

    expect($signatureRequestResponse)
        ->when($signatureRequestResponse->successful(), fn ($expectation) => $expectation->getRequestUrl()->toBeString()->toBe('https://test.example'))
        ->when($signatureRequestResponse->successful() === false, fn ($expectation) => $expectation->getRequestUrl()->toBeNull());
})->with([
    [200, [], ['request_url' => 'https://test.example']],
    [500, [], []],
]);
