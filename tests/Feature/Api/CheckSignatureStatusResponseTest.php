<?php

use AmaizingCompany\CertifactionClient\Api\Responses\CheckSignatureStatusResponse;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

pest()->group('api');

it ('can initiate instance', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));

    expect(new CheckSignatureStatusResponse($response))
        ->toBeInstanceOf(CheckSignatureStatusResponse::class);
})->with([
    [200, [], [
        'url' => 'https://test.example',
        'id' => 'test_id',
        'created_at' => '2025-01-10T14:58:35Z',
        'cancelled' => false,
        'envelope_items' => [
            [
                'legal_weight' => 'standard',
                'jurisdiction' => '',
                'status' => 'signed',
                'signed_at' => '2025-01-10T14:59:56Z',
                'file_url' => 'https://test.example',
                'file_id' => 'file_id',
                'comments' => [],
            ]
        ],
        'signer' => [
            'email' => 'test@example.com'
        ]
    ]],
    [500, [], []]
]);

it ('can get request url', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));
    $checkSignatureStatusResponse = new CheckSignatureStatusResponse($response);

    expect($checkSignatureStatusResponse)
        ->when($checkSignatureStatusResponse->successful(), fn($signatureStatusResponse) => $signatureStatusResponse->getSignatureRequestUrl()->toBeString()->toBe('https://test.example'))
        ->when($checkSignatureStatusResponse->successful() === false, fn($signatureStatusResponse) => $signatureStatusResponse->getSignatureRequestUrl()->toBeNull());

})->with([
    [200, [], [
        'url' => 'https://test.example',
        'id' => 'test_id',
        'created_at' => '2025-01-10T14:58:35Z',
        'cancelled' => false,
        'envelope_items' => [
            [
                'legal_weight' => 'standard',
                'jurisdiction' => '',
                'status' => 'signed',
                'signed_at' => '2025-01-10T14:59:56Z',
                'file_url' => 'https://test.example',
                'file_id' => 'file_id',
                'comments' => [],
            ]
        ],
        'signer' => [
            'email' => 'test@example.com'
        ]
    ]],
    [500, [], []]
]);

it ('can get request id', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));
    $checkSignatureStatusResponse = new CheckSignatureStatusResponse($response);

    expect($checkSignatureStatusResponse)
        ->when($checkSignatureStatusResponse->successful(), fn($signatureStatusResponse) => $signatureStatusResponse->getSignatureRequestId()->toBeString()->toBe('test_id'))
        ->when($checkSignatureStatusResponse->successful() === false, fn($signatureStatusResponse) => $signatureStatusResponse->getSignatureRequestId()->toBeNull());

})->with([
    [200, [], [
        'url' => 'https://test.example',
        'id' => 'test_id',
        'created_at' => '2025-01-10T14:58:35Z',
        'cancelled' => false,
        'envelope_items' => [
            [
                'legal_weight' => 'standard',
                'jurisdiction' => '',
                'status' => 'signed',
                'signed_at' => '2025-01-10T14:59:56Z',
                'file_url' => 'https://test.example',
                'file_id' => 'file_id',
                'comments' => [],
            ]
        ],
        'signer' => [
            'email' => 'test@example.com'
        ]
    ]],
    [500, [], []]
]);

it ('can get created at', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));
    $checkSignatureStatusResponse = new CheckSignatureStatusResponse($response);

    expect($checkSignatureStatusResponse)
        ->when(
            $checkSignatureStatusResponse->successful(),
            fn($signatureStatusResponse) =>
                $signatureStatusResponse->getCreatedAt()->toBeInstanceOf(Carbon::class))
        ->when(
            $checkSignatureStatusResponse->successful() === false,
            fn($signatureStatusResponse) => $signatureStatusResponse->getCreatedAt()->toBeNull()
        );
})->with([
    [200, [], [
        'url' => 'https://test.example',
        'id' => 'test_id',
        'created_at' => '2025-01-10T14:58:35Z',
        'cancelled' => false,
        'envelope_items' => [
            [
                'legal_weight' => 'standard',
                'jurisdiction' => '',
                'status' => 'signed',
                'signed_at' => '2025-01-10T14:59:56Z',
                'file_url' => 'https://test.example',
                'file_id' => 'file_id',
                'comments' => [],
            ]
        ],
        'signer' => [
            'email' => 'test@example.com'
        ]
    ]],
    [500, [], []]
]);

it ('can get get cancellation status', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));
    $checkSignatureStatusResponse = new CheckSignatureStatusResponse($response);

    expect($checkSignatureStatusResponse)
        ->when(
            $checkSignatureStatusResponse->successful(),
            fn($signatureStatusResponse) =>
            $signatureStatusResponse->isCancelled()->toBeBool()->toBeFalse())
        ->when(
            $checkSignatureStatusResponse->successful() === false,
            fn($signatureStatusResponse) => $signatureStatusResponse->isCancelled()->toBeNull()
        );
})->with([
    [200, [], [
        'url' => 'https://test.example',
        'id' => 'test_id',
        'created_at' => '2025-01-10T14:58:35Z',
        'cancelled' => false,
        'envelope_items' => [
            [
                'legal_weight' => 'standard',
                'jurisdiction' => '',
                'status' => 'signed',
                'signed_at' => '2025-01-10T14:59:56Z',
                'file_url' => 'https://test.example',
                'file_id' => 'file_id',
                'comments' => [],
            ]
        ],
        'signer' => [
            'email' => 'test@example.com'
        ]
    ]],
    [500, [], []]
]);

it ('can get get cancellation status when request was cancelled', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));
    $checkSignatureStatusResponse = new CheckSignatureStatusResponse($response);

    expect($checkSignatureStatusResponse->isCancelled())
        ->toBeBool()
        ->toBeTrue();
})->with([
    [200, [], [
        'url' => 'https://test.example',
        'id' => 'test_id',
        'created_at' => '2025-01-10T14:58:35Z',
        'cancelled' => true,
        'envelope_items' => [
            [
                'legal_weight' => 'standard',
                'jurisdiction' => '',
                'status' => 'signed',
                'signed_at' => '2025-01-10T14:59:56Z',
                'file_url' => 'https://test.example',
                'file_id' => 'file_id',
                'comments' => [],
            ]
        ],
        'signer' => [
            'email' => 'test@example.com'
        ]
    ]],
]);

it ('can get handle envelope items', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));
    $checkSignatureStatusResponse = new CheckSignatureStatusResponse($response);

    expect($checkSignatureStatusResponse->getEnvelopeItems())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(2)
        ->and($checkSignatureStatusResponse->getSignedEnvelopeItems())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(1)
        ->and($checkSignatureStatusResponse->getUnsignedEnvelopeItems())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(1)
        ->and($checkSignatureStatusResponse->hasAllItemsSigned())
        ->toBeBool()
        ->toBeFalse();
})->with([
    [200, [], [
        'url' => 'https://test.example',
        'id' => 'test_id',
        'created_at' => '2025-01-10T14:58:35Z',
        'cancelled' => false,
        'envelope_items' => [
            [
                'legal_weight' => 'standard',
                'jurisdiction' => '',
                'status' => 'signed',
                'signed_at' => '2025-01-10T14:59:56Z',
                'file_url' => 'https://test.example',
                'file_id' => 'file_id',
                'comments' => [],
            ],
            [
                'legal_weight' => 'standard',
                'jurisdiction' => '',
                'status' => 'unsigned',
                'signed_at' => '2025-01-10T14:59:56Z',
                'file_url' => 'https://test.example',
                'file_id' => 'file_id',
                'comments' => [],
            ]
        ],
        'signer' => [
            'email' => 'test@example.com'
        ]
    ]]
]);

it ('can get handle envelope items on bad response', function (int $status, array $headers, array $body) {
    $response = new Response($status, $headers, json_encode($body));
    $checkSignatureStatusResponse = new CheckSignatureStatusResponse($response);

    expect($checkSignatureStatusResponse->getEnvelopeItems())
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
})->with([
    [500, [], []]
]);
