<?php

namespace AmaizingCompany\CertifactionClient\Api\Requests;

use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Responses\InviteUserResponse;
use AmaizingCompany\CertifactionClient\Enums\CertifactionEnvironment;
use AmaizingCompany\CertifactionClient\Exceptions\ApiServerUriMissingException;
use Illuminate\Http\Client\ConnectionException;

final class InviteUserRequest extends BaseRequest implements Request
{
    protected string $email;

    protected string $organizationId;

    protected string $roleId;

    public function __construct(string $organizationId, string $email, string $roleId)
    {
        $this->organizationId($organizationId);
        $this->email($email);
        $this->roleId($roleId);
    }

    public static function make(string $organizationId, string $email, string $roleId): InviteUserRequest
    {
        return new self($organizationId, $email, $roleId);
    }

    public function email(string $email): InviteUserRequest
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getOrganizationId(): string
    {
        return $this->organizationId;
    }

    public function getRoleId(): string
    {
        return $this->roleId;
    }

    public function organizationId(string $organizationId): static
    {
        $this->organizationId = $organizationId;

        return $this;
    }

    public function roleId(string $roleId): static
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * @throws ApiServerUriMissingException
     * @throws ConnectionException
     */
    public function send(): InviteUserResponse
    {
        $response = self::makeRequest(CertifactionEnvironment::ADMIN)
            ->acceptJson()
            ->withBody(json_encode([
                'email' => $this->getEmail(),
                'role_id' => $this->getRoleId(),
            ]))
            ->post("/organization/{$this->getOrganizationId()}/user");

        return new InviteUserResponse($response->toPsrResponse());
    }
}
