<?php

namespace AmaizingCompany\CertifactionClient\Listeners;

use AmaizingCompany\CertifactionClient\Enums\AccountStatus;
use AmaizingCompany\CertifactionClient\Events\UserInvitedToCertifaction;

class CreateAccount
{
    public function handle(UserInvitedToCertifaction $event): void
    {
        $event->user->certifactionAccount()->create([
            'status' => AccountStatus::INVITED,
            'identified' => false,
            'admin' => false,
        ]);
    }
}
