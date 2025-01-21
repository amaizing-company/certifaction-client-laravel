<?php

use AmaizingCompany\CertifactionClient\Api\Requests\DeleteUserRequest;

pest()->group('api');

beforeEach(function () {
    $this->deleteUserRequest = new DeleteUserRequest('test_org_id');
});

it('can initiate instance', function () {
    expect($this->deleteUserRequest)
        ->toBeInstanceOf(DeleteUserRequest::class);
});

it('can prepare request for an existing user', function () {
    expect($this->deleteUserRequest->forExistingUser('test_id')->isExistingUser())
        ->toBeBool()
        ->toBeTrue()
        ->and($this->deleteUserRequest->getUserUid())
        ->toBeString()
        ->toBe('test_id');
});

it('can prepare request for an invited user', function () {
    expect($this->deleteUserRequest->forInvitedUser('test@example.com')->isExistingUser())
        ->toBeBool()
        ->toBeFalse()
        ->and($this->deleteUserRequest->getInvitationEmail())
        ->toBeString()
        ->toBe('test@example.com');
});

it('can get uid when not set', function () {
    expect($this->deleteUserRequest->getUserUid())
        ->toBeNull();
});

it('can get invitation email when not set', function () {
    expect($this->deleteUserRequest->getInvitationEmail())
        ->toBeNull();
});

it('can get organization id', function () {
    expect($this->deleteUserRequest->getOrganizationId())
        ->toBeString()
        ->toBe('test_org_id');
});
