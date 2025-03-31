<?php

namespace AmaizingCompany\CertifactionClient\Events;

use AmaizingCompany\CertifactionClient\Contracts\Account;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserJoinedCertifaction extends BaseEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public Account $account)
    {

    }

    public function broadcastOn()
    {
        return new PrivateChannel("certifaction.user.{$this->account->user->getKey()}");
    }
}
