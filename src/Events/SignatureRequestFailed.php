<?php

namespace AmaizingCompany\CertifactionClient\Events;

use AmaizingCompany\CertifactionClient\Contracts\Events\SignatureRequestFailed as SignatureRequestFailedContract;
use AmaizingCompany\CertifactionClient\Contracts\SignatureTransaction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SignatureRequestFailed extends BaseEvent implements ShouldBroadcast, SignatureRequestFailedContract
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public SignatureTransaction $transaction) {}

    public function broadcastOn()
    {
        return new PrivateChannel("certifaction.signature_transaction.{$this->transaction->getKey()}");
    }
}
