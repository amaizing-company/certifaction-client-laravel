<?php

namespace AmaizingCompany\CertifactionClient\Jobs;

use AmaizingCompany\CertifactionClient\Api\DataObjects\UserItem;
use AmaizingCompany\CertifactionClient\Enums\AccountStatus;
use AmaizingCompany\CertifactionClient\Events\UserJoinedCertifaction;
use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;
use AmaizingCompany\CertifactionClient\Contracts\Account;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessAccountSync implements ShouldQueue
{
    public function handle(): void
    {
        $organization = CertifactionClient::getOrganization();

        $users = $organization->getUsers();

        $accounts = app(Account::class)::query()
            ->where('status', AccountStatus::INVITED)
            ->get();

        if (empty($accounts) || $users->isEmpty()) {
            return;
        }

        /**
         * @var Account $account
         * @var UserItem $user
         */
        foreach ($accounts as $account) {
            $user = $users->where('inviteEmail', $account->invite_email)->first();

            if (empty($user)) {
                continue;
            }

            if ($user->getStatus() !== $account->status) {
                $account->status = AccountStatus::from($user->getStatus());
                $account->save();

                if ($account->status === AccountStatus::JOINED) {
                    UserJoinedCertifaction::dispatch($account);
                }
            }
        }
    }
}
