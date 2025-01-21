<?php

use AmaizingCompany\CertifactionClient\Api\RoleItem;
use AmaizingCompany\CertifactionClient\Api\TeamspaceItem;
use AmaizingCompany\CertifactionClient\Api\UserItem;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

pest()->group('api');

beforeEach(function () {
    $this->userItem = new UserItem();
});

it ('can initiate instance', function () {
    expect($this->userItem)
        ->toBeInstanceOf(UserItem::class);
});

it ('can handle admin option', function () {
    expect($this->userItem->isAdmin())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->userItem->admin()->isAdmin())
        ->toBeTrue()
        ->and($this->userItem->admin(false)->isAdmin())
        ->toBeFalse();
});

it ('can handle role items', function () {
    expect($this->userItem->getRoles())
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty()
        ->and($this->userItem->addRole(new RoleItem('test_id', 'test name'))->getRoles())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(1)
        ->and($this->userItem->addRole(new RoleItem('test_id', 'test name'))->getRoles())
        ->toHaveCount(2);
});

it ('can handle teamspace items', function () {
    expect($this->userItem->getTeamspaces())
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty()
        ->and($this->userItem->addTeamspace(new TeamspaceItem('test_id', 'test name'))->getTeamspaces())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(1)
        ->and($this->userItem->addTeamspace(new TeamspaceItem('test_id', 'test name'))->getTeamspaces())
        ->toHaveCount(2);
});

it ('can handle citizenship', function () {
    expect($this->userItem->getCitizenship())
        ->toBeNull()
        ->and($this->userItem->citizenship('germany')->getCitizenship())
        ->toBeString()
        ->toBe('germany');
});

it ('can handle date of creation', function () {
    expect($this->userItem->getCreatedAt())
        ->toBeNull()
        ->and($this->userItem->createdAt('0001-01-01T00:00:00Z')->getCreatedAt())
        ->toBeInstanceOf(Carbon::class)
        ->and($this->userItem->getCreatedAt()->format('d.m.Y H:i:s'))
        ->toBeString()
        ->toBe('01.01.0001 00:00:00')
        ->and($this->userItem->createdAt(Carbon::create('0001-01-01T00:00:00Z'))->getCreatedAt())
        ->toBeInstanceOf(Carbon::class)
        ->and($this->userItem->getCreatedAt()->format('d.m.Y H:i:s'))
        ->toBeString()
        ->toBe('01.01.0001 00:00:00');
});

it ('can handle date of creation with invalid format', function () {
    expect($this->userItem->createdAt('no valid datetime'));
})->throws(InvalidFormatException::class);

it ('can handle email', function () {
    expect($this->userItem->getEmail())
        ->toBeNull()
        ->and($this->userItem->email('test@example.com')->getEmail())
        ->toBeString()
        ->toBe('test@example.com');
});

it ('can handle external id', function () {
    expect($this->userItem->getExternalId())
        ->toBeNull()
        ->and($this->userItem->externalId('test_id')->getExternalId())
        ->toBeString()
        ->toBe('test_id');
});

it ('can handle id', function () {
    expect($this->userItem->getId())
        ->toBeNull()
        ->and($this->userItem->id('test_id')->getId())
        ->toBeString()
        ->toBe('test_id');
});

it ('can handle invite email', function () {
    expect($this->userItem->getInviteEmail())
        ->toBeNull()
        ->and($this->userItem->inviteEmail('test@example.com')->getInviteEmail())
        ->toBeString()
        ->toBe('test@example.com');
});

it ('can handle inviter', function () {
    expect($this->userItem->getInviter())
        ->toBeNull()
        ->and($this->userItem->inviter(new UserItem())->getInviter())
        ->toBeInstanceOf(UserItem::class);
});

it ('can handle name', function () {
    expect($this->userItem->getName())
        ->toBeNull()
        ->and($this->userItem->name('Max Muster')->getName())
        ->toBeString()
        ->toBe('Max Muster');
});

it ('can handle status', function () {
    expect($this->userItem->getStatus())
        ->toBeNull()
        ->and($this->userItem->status('joined')->getStatus())
        ->toBeString()
        ->toBe('joined');
});

it ('can handle uid', function () {
    expect($this->userItem->getUid())
        ->toBeNull()
        ->and($this->userItem->uid('test_id')->getUid())
        ->toBeString()
        ->toBe('test_id');
});

it ('can check if has inviter', function () {
    expect($this->userItem->hasInviter())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->userItem->inviter(new UserItem())->hasInviter())
        ->toBeTrue();
});

it ('can check if has roles', function () {
    expect($this->userItem->hasRoles())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->userItem->addRole(new RoleItem('test_id', 'test name'))->hasRoles())
        ->toBeTrue();
});

it ('can check if has teamspaces', function () {
    expect($this->userItem->hasTeamspaces())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->userItem->addTeamspace(new TeamspaceItem('test_id', 'test name'))->hasTeamspaces())
        ->toBeTrue();
});

it ('can handle name verified option', function () {
    expect($this->userItem->isNameVerified())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->userItem->nameVerified()->isNameVerified())
        ->toBeTrue()
        ->and($this->userItem->nameVerified(false)->isNameVerified())
        ->toBeFalse();
});

it ('can handle name organization option', function () {
    expect($this->userItem->isOrganization())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->userItem->organization()->isOrganization())
        ->toBeTrue()
        ->and($this->userItem->organization(false)->isOrganization())
        ->toBeFalse();
});
