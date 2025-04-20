<?php

namespace AmaizingCompany\CertifactionClient\Events;

use AmaizingCompany\CertifactionClient\Contracts\Document;
use AmaizingCompany\CertifactionClient\Contracts\Events\DocumentPrepared as DocumentPreparedContract;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentPrepared extends BaseEvent implements DocumentPreparedContract, ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public Document $document) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel("certifaction.document.{$this->document->getKey()}");
    }
}
