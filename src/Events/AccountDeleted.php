<?php

namespace AmaizingCompany\CertifactionClient\Events;

use AmaizingCompany\CertifactionClient\Contracts\CertifactionUser;
use AmaizingCompany\CertifactionClient\Contracts\Events\AccountDeleted as AccountDeletedContract;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AccountDeleted extends BaseEvent implements ShouldBroadcast, AccountDeletedContract
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public CertifactionUser $user) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel("certifaction.user.{$this->user->getKey()}");
    }
}
