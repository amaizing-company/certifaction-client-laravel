<?php

namespace AmaizingCompany\CertifactionClient\Listeners;

use AmaizingCompany\CertifactionClient\Contracts\Events\UserInvitedToCertifaction;
use AmaizingCompany\CertifactionClient\Enums\AccountStatus;

class CreateAccount
{
    public function handle(UserInvitedToCertifaction $event): void
    {
        $event->user->certifactionAccount()->create([
            'invite_email' => $event->user->getEmail(),
            'status' => AccountStatus::INVITED,
            'identified' => false,
            'admin' => false,
            'role_id' => $event->roleId,
        ]);
    }
}
