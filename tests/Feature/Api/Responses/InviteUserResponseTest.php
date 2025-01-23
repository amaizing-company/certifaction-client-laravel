<?php

use AmaizingCompany\CertifactionClient\Api\Responses\InviteUserResponse;
use GuzzleHttp\Psr7\Response;

it('can initiate instance', function ($status) {
    $response = new Response($status, [], null);

    expect(new InviteUserResponse($response))
        ->toBeInstanceOf(InviteUserResponse::class);
})->with([
    [200],
    [500],
]);
