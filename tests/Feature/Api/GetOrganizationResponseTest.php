<?php

use AmaizingCompany\CertifactionClient\Api\Responses\GetOrganizationResponse;
use AmaizingCompany\CertifactionClient\Api\UserItem;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;

pest()->group('api');

beforeEach(function () {
    $validResponse = new Response(200, [], json_encode($this->getOrganizationResponseData()));
    $badResponse = new Response(500, [], null);

    $this->validOrganizationResponse = new GetOrganizationResponse($validResponse);
    $this->badOrganizationResponse = new GetOrganizationResponse($badResponse);
});

it ('can initiate instance', function () {
    expect($this->validOrganizationResponse)
        ->toBeInstanceOf(GetOrganizationResponse::class)
        ->and($this->badOrganizationResponse)
        ->toBeInstanceOf(GetOrganizationResponse::class);
});

it ('can get credit type', function () {
    expect($this->validOrganizationResponse->getCreditType())
        ->toBeString()
        ->toBe('flat_rate')
        ->and($this->badOrganizationResponse->getCreditType())
        ->toBeNull();
});

it ('can get id', function () {
    expect($this->validOrganizationResponse->getId())
        ->toBeString()
        ->toBe('test_org_id')
        ->and($this->badOrganizationResponse->getId())
        ->toBeNull();
});

it ('can check if has legacy credits', function () {
    expect($this->validOrganizationResponse->hasLegacyCredits())
        ->toBeBool()
        ->toBeTrue()
        ->and($this->badOrganizationResponse->hasLegacyCredits())
        ->toBeBool()
        ->toBeFalse();
});

it ('can get name', function () {
    expect($this->validOrganizationResponse->getName())
        ->toBeString()
        ->toBe('test org name')
        ->and($this->badOrganizationResponse->getName())
        ->toBeNull();
});

it ('can check if has name verified', function () {
    expect($this->validOrganizationResponse->isNameVerified())
        ->toBeBool()
        ->toBeTrue()
        ->and($this->badOrganizationResponse->isNameVerified())
        ->toBeBool()
        ->toBeFalse();
});

it ('can get quota', function () {
    expect($this->validOrganizationResponse->getQuota())
        ->toBeInt()
        ->toBe(0)
        ->and($this->badOrganizationResponse->getQuota())
        ->toBeNull();
});

it ('can get roles', function () {
    expect($this->validOrganizationResponse->getRoles())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(2)
        ->and($this->badOrganizationResponse->getRoles())
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});

it ('can parse roles', function () {
    $role = $this->validOrganizationResponse->getRoles()->first();
    expect($role->getId())
        ->toBe('test_admin_role_id')
        ->and($role->getName())
        ->toBe('Admin')
        ->and($role->isDefault())
        ->toBeFalse()
        ->and($role->isAdmin())
        ->toBeTrue();
});

it ('can get users', function () {
    expect($this->validOrganizationResponse->getUsers())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(2)
        ->and($this->badOrganizationResponse->getUsers())
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});

it ('can parse users', function () {
    /**
     * @var UserItem $user
     */
    $user = $this->validOrganizationResponse->getUsers()->first();

    expect($user->getId())
        ->toBe('123456')
        ->and($user->getUid())
        ->toBe('test_uid')
        ->and($user->getExternalId())
        ->toBeNull()
        ->and($user->getEmail())
        ->toBe('test@example.com')
        ->and($user->getName())
        ->toBe('Robert Smith')
        ->and($user->isNameVerified())
        ->toBeTrue()
        ->and($user->getCitizenship())
        ->toBeNull()
        ->and($user->hasLegacyCredits())
        ->toBeTrue()
        ->and($user->getInviter())
        ->toBeInstanceOf(UserItem::class)
        ->and($user->getRoles())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(1)
        ->and($user->getTeamspaces())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(1)
        ->and($user->getStatus())
        ->toBe('joined')
        ->and($user->getInviteEmail())
        ->toBeNull()
        ->and($user->isAdmin())
        ->toBeTrue()
        ->and($user->isOrganization())
        ->toBeFalse();
});


