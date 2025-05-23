<?php

namespace AmaizingCompany\CertifactionClient\Events;

use AmaizingCompany\CertifactionClient\Contracts\Events\IdentificationRequestFinished as IdentificationRequestFinishedContract;
use AmaizingCompany\CertifactionClient\Contracts\IdentityTransaction;
use AmaizingCompany\CertifactionClient\Enums\IdentificationStatus;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IdentificationRequestFinished extends BaseEvent implements IdentificationRequestFinishedContract, ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public IdentityTransaction $identityTransaction, public IdentificationStatus $status) {}

    public function broadcastOn()
    {
        return new PrivateChannel("certifaction.identity_transaction.{$this->identityTransaction->getKey()}");
    }
}
