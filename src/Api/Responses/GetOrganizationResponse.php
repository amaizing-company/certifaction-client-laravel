<?php

namespace AmaizingCompany\CertifactionClient\Api\Responses;

use AmaizingCompany\CertifactionClient\Api\Responses\Contracts\CertifactionResponse;
use AmaizingCompany\CertifactionClient\Api\RoleItem;
use AmaizingCompany\CertifactionClient\Api\TeamspaceItem;
use AmaizingCompany\CertifactionClient\Api\UserItem;
use Illuminate\Support\Collection;
use Psr\Http\Message\MessageInterface;

class GetOrganizationResponse extends BaseResponse implements CertifactionResponse
{
    protected Collection $roles;
    protected Collection $users;

    protected function boot(): void
    {
        $this->roles = Collection::empty();
        $this->users = Collection::empty();

        $this->parseRoles();
        $this->parseUsers();
    }

    protected function getValueByKey(string $key, mixed $default = null): mixed
    {
        $object = Collection::make(Collection::make($this->json())->first());

        return $object->get($key, $default);
    }

    public function getCreditType(): ?string
    {
        return $this->getValueByKey('credit_type');
    }

    public function getId(): ?string
    {
        return $this->getValueByKey('id');
    }

    public function hasLegacyCredits(): bool
    {
        return $this->getValueByKey('legacy_credits', false);
    }

    public function getName(): ?string
    {
        return $this->getValueByKey('name');
    }

    public function isNameVerified(): bool
    {
        return $this->getValueByKey('name_verified', false);
    }

    public function getQuota(): ?int
    {
        return $this->getValueByKey('quota');
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function getSubscriptionType(): ?string
    {
        return $this->getValueByKey('subscription_type');
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    protected function parseRoles(): void
    {
        $roles = $this->getValueByKey('roles', []);

        foreach ($roles as $role) {
            $role = Collection::make($role);
            $item = new RoleItem($role->get('id', ''), $role->get('name', ''));

            if ($role->get('default', false)) {
                $item->default();
            }

            if ($role->get('admin', false)) {
                $item->admin();
            }

            $this->roles->add($item);
        }
    }

    protected function parseUsers(): void
    {
        $users = $this->getValueByKey('organization_users', []);

        foreach ($users as $user) {
            $userItems = Collection::make($user);
            $inviter = Collection::make($userItems->get('inviter'));
            $user = Collection::make($userItems->get('user'));
            $roles = Collection::make($userItems->get('roles'));
            $teamspaces = Collection::make($userItems->get('teamspaces'));
            $item = new UserItem();

            $this->parseUserItemUser($item, $user, $userItems);
            $this->parseUserItemInviter($item, $inviter);
            $this->parseUserItemRoles($item, $roles);
            $this->parseUserItemTeamspaces($item, $teamspaces);

            $this->users->add($item);
        }
    }

    protected function parseUserItemUser(UserItem $userItem, Collection $user, Collection $userItems): UserItem
    {
        $userItemParamsMap = [
            'id' => $user->get('id'),
            'uid' => $user->get('uid'),
            'externalId' => $user->get('external_id'),
            'email' => $user->get('email'),
            'name' => $user->get('name'),
            'nameVerified' => $user->get('name_verified'),
            'citizenship' => $user->get('citizenship'),
            'legacyCredits' => $user->get('legacy_credits'),
            'createdAt' => $user->get('created_at'),
            'status' => $userItems->get('status'),
            'inviteEmail' => $userItems->get('invite_email'),
            'organization' => $userItems->get('organization'),
            'admin' => $userItems->get('is_admin'),
        ];

        return $this->processParamsFromMap($userItemParamsMap, $userItem);
    }

    protected function parseUserItemInviter(UserItem $userItem, Collection $inviter): UserItem
    {
        $inviterItemParamsMap = [
            'externalId' => $inviter->get('external_id'),
            'nameVerified' => $inviter->get('name_verified'),
            'citizenship' => $inviter->get('citizenship'),
            'legacyCredits' => $inviter->get('legacy_credits'),
            'createdAt' => $inviter->get('created_at'),
        ];

        $inviterItem = new UserItem();

        return $userItem->inviter($this->processParamsFromMap($inviterItemParamsMap, $inviterItem));
    }

    protected function processParamsFromMap(array $map, mixed &$item): mixed
    {
        foreach ($map as $key => $value) {
            if (empty($value)) {
                continue;
            }

            $item->$key($value);
        }

        return $item;
    }

    protected function parseUserItemRoles(UserItem $userItem, Collection $roles): UserItem
    {
        foreach ($roles as $role) {
            $role = Collection::make($role);
            $roleItem = new RoleItem($role->get('id', ''), $role->get('name', ''));

            if ($role->get('default')) {
                $roleItem->default();
            }

            if ($role->get('admin')) {
                $roleItem->admin();
            }

           $userItem->addRole($roleItem);
        }

        return $userItem;
    }

    protected function parseUserItemTeamspaces(UserItem $userItem, Collection $teamspaces): UserItem
    {
        foreach ($teamspaces as $teamspace) {
            $teamspace = Collection::make($teamspace);
            $teamspaceItem = new TeamspaceItem($teamspace->get('id', ''), $teamspace->get('name', ''));

            $userItem->addTeamspace($teamspaceItem);
        }

        return $userItem;
    }
}
