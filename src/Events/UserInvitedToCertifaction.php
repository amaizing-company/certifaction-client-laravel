<?php

namespace AmaizingCompany\CertifactionClient\Events;

use AmaizingCompany\CertifactionClient\Contracts\CertifactionUser;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserInvitedToCertifaction extends BaseEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public CertifactionUser $user) {}

    public function broadcastOn()
    {
        return new PrivateChannel("certifaction.user.{$this->user->id}");
    }
}
