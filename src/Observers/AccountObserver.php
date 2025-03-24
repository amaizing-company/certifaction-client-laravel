<?php

namespace AmaizingCompany\CertifactionClient\Observers;

use AmaizingCompany\CertifactionClient\Contracts\Account;
use AmaizingCompany\CertifactionClient\Facades\CertifactionClient;

class AccountObserver
{
    public function created(Account $account) {}

    public function deleting(Account $account): false
    {
        CertifactionClient::requestAccountDeletion($account);

        return false;
    }
}
