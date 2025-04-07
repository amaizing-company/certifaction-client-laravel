<?php

namespace AmaizingCompany\CertifactionClient\Jobs;

use AmaizingCompany\CertifactionClient\Api\Requests\InviteUserRequest;
use AmaizingCompany\CertifactionClient\Contracts\CertifactionUser;
use AmaizingCompany\CertifactionClient\Events\UserInvitedToCertifaction;
use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessUserInvitation implements ShouldQueue
{
    use Queueable;

    public function __construct(public CertifactionUser|Model $user, public ?string $roleId = null)
    {
        if (empty($this->roleId)) {
            $this->roleId = CertifactionClient::getDefaultRoleId();
        }
    }

    public function handle()
    {
        $organizationId = CertifactionClient::getOrganization()->getId();

        try {
            $response = InviteUserRequest::make($organizationId, $this->user->getEmail(), $this->roleId)->send()->throw();
        } catch (\Throwable $e) {
            Log::warning($e->getMessage(), ['user_id' => $this->user->getKey()]);

            return;
        }

        if ($response->isInvited()) {
            UserInvitedToCertifaction::dispatch($this->user, $this->roleId);
        }
    }
}
