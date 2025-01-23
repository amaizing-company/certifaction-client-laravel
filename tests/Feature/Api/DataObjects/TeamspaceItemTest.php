<?php

use AmaizingCompany\CertifactionClient\Api\DataObjects\TeamspaceItem;

pest()->group('api', 'data-objects');

beforeEach(function () {
    $this->teamspaceItem = new TeamspaceItem('test_id', 'test name');
});

it('can initiate instance', function () {
    expect($this->teamspaceItem)
        ->toBeInstanceOf(TeamspaceItem::class);
});

it('can get id', function () {
    expect($this->teamspaceItem->getId())
        ->toBeString()
        ->toBe('test_id');
});

it('can get name', function () {
    expect($this->teamspaceItem->getName())
        ->toBeString()
        ->toBe('test name');
});
