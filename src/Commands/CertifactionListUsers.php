<?php

namespace AmaizingCompany\CertifactionClient\Commands;

use AmaizingCompany\CertifactionClient\Api\DataObjects\UserItem;
use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;
use Illuminate\Console\Command;

class CertifactionListUsers extends Command
{
    protected $signature = 'certifaction:users:list
                            {--remote : Fetch roles from remote server}
                            {--email= : Search for specific user email}';

    protected $description = 'List all certifaction users';

    public function handle()
    {
        $users = CertifactionClient::getOrganization($this->option('remote'))->getUsers();

        if ($this->option('email')) {
            $users = $users->filter(function (UserItem $user) {
                return $user->getEmail() === $this->option('email');
            });
        }

        $this->table([
            'ID',
            'UID',
            'External ID',
            'Email',
            'Name',
            'Name is verified',
            'Citizenship',
            'Has legacy credits',
            'Created at',
            'Status',
            'Invited with email',
            'Organization',
            'Admin',
            'Invited by',
            'Roles',
            'Teamspaces',
        ], $users->map(function (UserItem $user) {
            return [
                $user->getId(),
                $user->getUid(),
                $user->getExternalId(),
                $user->getEmail(),
                $user->getName(),
                $user->isNameVerified() ? 'yes' : 'no',
                $user->getCitizenship(),
                $user->hasLegacyCredits() ? 'yes' : 'no',
                $user->getCreatedAt(),
                $user->getStatus(),
                $user->getInviteEmail(),
                $user->isOrganization() ? 'yes' : 'no',
                $user->isAdmin() ? 'yes' : 'no',
                $user->getInviter()->getEmail(),
                $user->getRoles()->map(function ($role) {
                    return $role->getName();
                })->implode(', '),
                $user->getTeamspaces()->map(function ($teamspace) {
                    return $teamspace->getName();
                })->implode(', '),
            ];
        }));

        return self::SUCCESS;
    }
}
