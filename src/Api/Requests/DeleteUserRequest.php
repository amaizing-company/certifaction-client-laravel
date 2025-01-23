<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Requests\Contracts\Request;
use AmaizingCompany\CertifactionClient\CertifactionClient;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;

final class DeleteUserRequest implements Request
{
    protected bool $existingUser = false;

    protected ?string $uid = null;

    protected ?string $invitationEmail = null;

    protected string $organizationId;

    public function __construct(string $organizationId)
    {
        $this->organizationId($organizationId);
    }

    public function forExistingUser(string $uid): static
    {
        $this->existingUser = true;
        $this->uid = $uid;

        return $this;
    }

    public function forInvitedUser(string $email): static
    {
        $this->existingUser = false;
        $this->invitationEmail = $email;

        return $this;
    }

    public function getInvitationEmail(): ?string
    {
        return $this->invitationEmail;
    }

    public function getOrganizationId(): string
    {
        return $this->organizationId;
    }

    public function getUserUid(): ?string
    {
        return $this->uid;
    }

    public function isExistingUser(): bool
    {
        return $this->existingUser;
    }

    public function organizationId(string $id): static
    {
        $this->organizationId = $id;

        return $this;
    }

    /**
     * @throws ApiServerUriMissingException
     * @throws ConnectionException
     * @throws Exception
     */
    public function send(): PromiseInterface|Response
    {
        if ($this->isExistingUser()) {
            $body = ['user_uid' => $this->uid];
        } else {
            $body = ['invite_email' => $this->invitationEmail];
        }

        return CertifactionClient::makeRequest(CertifactionEnvironment::ADMIN)
            ->withBody(json_encode($body))
            ->delete("/organization/{$this->getOrganizationId()}/user");
    }
}
