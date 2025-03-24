<?php

namespace AmaizingCompany\CertifactionClient\Events;

use AmaizingCompany\CertifactionClient\Contracts\IdentityTransaction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IdentitifcationRequestStarted extends BaseEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public IdentityTransaction $identityTransaction) {}

    public function broadcastOn()
    {
        return new PrivateChannel("certifaction.identity_transaction.{$this->identityTransaction->id}");
    }
}
