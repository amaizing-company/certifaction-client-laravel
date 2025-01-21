<?php

use AmaizingCompany\CertifactionClient\Api\Requests\InviteUserRequest;

pest()->group('api');

beforeEach(function () {
    $this->inviteUserRequest = new InviteUserRequest('test_org_id', 'test@example.com', 'test_role_id');
});

it('can initiate instance', function () {
    expect($this->inviteUserRequest)
        ->toBeInstanceOf(InviteUserRequest::class);
});

it('can handle organization id', function () {
    expect($this->inviteUserRequest->getOrganizationId())
        ->toBeString()
        ->toBe('test_org_id')
        ->and($this->inviteUserRequest->organizationId('modified_id')->getOrganizationId())
        ->toBeString()
        ->toBe('modified_id');
});

it('can handle role id', function () {
    expect($this->inviteUserRequest->getRoleId())
        ->toBeString()
        ->toBe('test_role_id')
        ->and($this->inviteUserRequest->roleId('modified_id')->getRoleId())
        ->toBeString()
        ->toBe('modified_id');
});

it('can handle email', function () {
    expect($this->inviteUserRequest->getEmail())
        ->toBeString()
        ->toBe('test@example.com')
        ->and($this->inviteUserRequest->email('modified@example.com')->getEmail())
        ->toBeString()
        ->toBe('modified@example.com');
});
