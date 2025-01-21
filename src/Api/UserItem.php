<?php

namespace AmaizingCompany\CertifactionClient\Api;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class UserItem
{
    protected bool $admin = false;

    protected ?string $citizenship = null;

    protected ?Carbon $createdAt = null;

    protected ?string $email = null;

    protected ?string $externalId = null;

    protected ?string $id = null;

    protected ?UserItem $inviter = null;

    protected ?string $inviteEmail = null;

    protected ?string $name = null;

    protected bool $nameVerified = false;

    protected bool $organization = false;

    protected Collection $roles;

    protected ?string $status = null;

    protected Collection $teamspaces;

    protected ?string $uid = null;

    protected bool $legacyCredits = false;

    public function __construct()
    {
        $this->roles = Collection::empty();
        $this->teamspaces = Collection::empty();
    }

    public function admin(bool $condition = true): static
    {
        $this->admin = $condition;

        return $this;
    }

    public function addRole(RoleItem $roleItem): static
    {
        $this->roles->add($roleItem);

        return $this;
    }

    public function addTeamspace(TeamspaceItem $teamspace): static
    {
        $this->teamspaces->add($teamspace);

        return $this;
    }

    public function citizenship(string $citizenship): static
    {
        $this->citizenship = $citizenship;

        return $this;
    }

    public function createdAt(string|Carbon $datetime): static
    {
        if (is_string($datetime)) {
            $this->createdAt = Carbon::create($datetime);
        } else {
            $this->createdAt = $datetime;
        }

        return $this;
    }

    public function email(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function externalId(string $id): static
    {
        $this->externalId = $id;

        return $this;
    }

    public function getCitizenship(): ?string
    {
        return $this->citizenship;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getInviteEmail(): ?string
    {
        return $this->inviteEmail;
    }

    public function getInviter(): ?UserItem
    {
        return $this->inviter;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getTeamspaces(): Collection
    {
        return $this->teamspaces;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function hasInviter(): bool
    {
        return $this->getInviter() !== null;
    }

    public function hasLegacyCredits(): bool
    {
        return $this->legacyCredits;
    }

    public function hasRoles(): bool
    {
        return $this->roles->isNotEmpty();
    }

    public function hasTeamspaces(): bool
    {
        return $this->teamspaces->isNotEmpty();
    }

    public function id(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function inviter(UserItem $user): static
    {
        $this->inviter = $user;

        return $this;
    }

    public function inviteEmail(string $email): static
    {
        $this->inviteEmail = $email;

        return $this;
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }

    public function isNameVerified(): bool
    {
        return $this->nameVerified;
    }

    public function isOrganization(): bool
    {
        return $this->organization;
    }

    public function legacyCredits(bool $condition = true): static
    {
        $this->legacyCredits = $condition;

        return $this;
    }

    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function nameVerified(bool $condition = true): static
    {
        $this->nameVerified = $condition;

        return $this;
    }

    public function organization(bool $condition = true): static
    {
        $this->organization = $condition;

        return $this;
    }

    public function status(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function uid(string $id): static
    {
        $this->uid = $id;

        return $this;
    }
}
