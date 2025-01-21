<?php

use AmaizingCompany\CertifactionClient\Api\Signer;

pest()->group('api');

beforeEach(function () {
    $this->signer = new Signer('test@example.com');
});

it('can initiate instance', function () {
    expect($this->signer)
        ->toBeInstanceOf(Signer::class);
});

it('can be converted into an array', function () {
    expect($this->signer->toArray())
        ->toBeArray()
        ->toHaveKeys([
            'birthdate',
            'citizenship',
            'domicile',
            'email',
            'first-name',
            'gender',
            'last-name',
            'middle-names',
            'mobile-phone',
            'name',
        ]);
});
