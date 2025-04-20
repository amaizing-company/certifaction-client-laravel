<?php

namespace AmaizingCompany\CertifactionClient\Events;

use AmaizingCompany\CertifactionClient\Contracts\Account;
use AmaizingCompany\CertifactionClient\Contracts\Events\UserJoinedCertifaction as UserJoinedCertifactionContract;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserJoinedCertifaction extends BaseEvent implements ShouldBroadcast, UserJoinedCertifactionContract
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public Account $account) {}

    public function broadcastOn()
    {
        return new PrivateChannel("certifaction.user.{$this->account->user->getKey()}");
    }
}
