<?php

namespace AmaizingCompany\CertifactionClient\Jobs;

use AmaizingCompany\CertifactionClient\Api\Requests\DeleteUserRequest;
use AmaizingCompany\CertifactionClient\CertifactionClient;
use AmaizingCompany\CertifactionClient\Contracts\Account;
use AmaizingCompany\CertifactionClient\Contracts\Events\AccountDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessAccountDeletion implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Account $account) {}

    public function handle(): void
    {
        $organizationId = CertifactionClient::getOrganization()->getId();

        $request = new DeleteUserRequest($organizationId);
        $user = $this->account->user;

        if ($this->account->isInvited()) {
            $request->forInvitedUser($user->getEmail());
        }

        if ($this->account->isJoined()) {
            $request->forExistingUser($user->getEmail());
        }

        try {
            $response = $request->send()->throw();
        } catch (Throwable $e) {
            Log::warning($e->getMessage(), ['account_id' => $this->account->getKey()]);

            return;
        }

        if ($response->successful()) {
            $this->account->deleteQuietly();

            Event::dispatch(app(AccountDeleted::class, ['user' => $user]));
        }
    }
}
