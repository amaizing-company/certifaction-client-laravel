<?php

namespace AmaizingCompany\CertifactionClient\Events;

use AmaizingCompany\CertifactionClient\Contracts\Events\FileTransactionFinished as FileTransactionFinishedContract;
use AmaizingCompany\CertifactionClient\Contracts\FileTransaction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileTransactionFinished extends BaseEvent implements FileTransactionFinishedContract, ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public FileTransaction $transaction) {}

    public function broadcastOn()
    {
        return new PrivateChannel("certifaction.file_transaction.{$this->transaction->getKey()}");
    }
}
