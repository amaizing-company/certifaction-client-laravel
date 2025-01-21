<?php

use AmaizingCompany\CertifactionClient\Api\RoleItem;

pest()->group('api');

beforeEach(function () {
    $this->roleItem = new RoleItem('test_id', 'test name');
});

it('can initiate instance', function () {
    expect($this->roleItem)
        ->toBeInstanceOf(RoleItem::class);
});

it('can handle id', function () {
    expect($this->roleItem->getId())
        ->toBeString()
        ->toBe('test_id')
        ->and($this->roleItem->id('modified_id')->getId())
        ->toBe('modified_id');
});

it('can handle name', function () {
    expect($this->roleItem->getName())
        ->toBeString()
        ->toBe('test name')
        ->and($this->roleItem->name('modified name')->getName())
        ->toBe('modified name');
});

it('can handle default option', function () {
    expect($this->roleItem->isDefault())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->roleItem->default()->isDefault())
        ->toBeTrue()
        ->and($this->roleItem->default(false)->isDefault())
        ->toBeFalse();
});

it('can handle admin option', function () {
    expect($this->roleItem->isAdmin())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->roleItem->admin()->isAdmin())
        ->toBeTrue()
        ->and($this->roleItem->admin(false)->isAdmin())
        ->toBeFalse();
});
