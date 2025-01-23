<?php

use AmaizingCompany\CertifactionClient\Api\QesStatusItem;
use AmaizingCompany\CertifactionClient\Enums\Jurisdiction;

pest()->group('api');

beforeEach(function () {
    $this->qesStatusItem = new QesStatusItem('eIDAS', 'USER_SIGNING_STATUS_NOT_FOUND', '');
});

it('can initiate instance', function () {
    expect($this->qesStatusItem)
        ->toBeInstanceOf(QesStatusItem::class);
});

it('can handle jurisdiction', function () {
    expect($this->qesStatusItem->getJurisdiction())
        ->toBeInstanceOf(Jurisdiction::class)
        ->toBe(Jurisdiction::EIDAS)
        ->and($this->qesStatusItem->jurisdiction('ZertES')->getJurisdiction())
        ->toBe(Jurisdiction::ZERTES);
});

it('can handle status', function () {
    expect($this->qesStatusItem->getStatus())
        ->toBeString()
        ->toBe('USER_SIGNING_STATUS_NOT_FOUND')
        ->and($this->qesStatusItem->status('test_status')->getStatus())
        ->toBe('test_status');
});

it('can handle signature level', function () {
    expect($this->qesStatusItem->getSignatureLevel())
        ->toBeNull()
        ->and($this->qesStatusItem->signatureLevel('test')->getSignatureLevel())
        ->toBeString()
        ->toBe('test');
});
