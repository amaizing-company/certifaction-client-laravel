<?php

use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Requests\BaseRequest;
use AmaizingCompany\CertifactionClient\Api\Responses\BaseResponse;
use AmaizingCompany\CertifactionClient\Contracts\FileTransaction;
use AmaizingCompany\CertifactionClient\Contracts\IdentityTransaction;
use AmaizingCompany\CertifactionClient\Contracts\SignatureTransaction;
use AmaizingCompany\CertifactionClient\Events\BaseEvent;
use AmaizingCompany\CertifactionClient\Models\Account;
use AmaizingCompany\CertifactionClient\Models\Document;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\Response;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Facade;

arch('it will not use debugging functions')
    ->expect(['dd', 'ddd', 'die', 'dump', 'ray', 'sleep'])
    ->each->not->toBeUsed();

arch()
    ->expect('AmaizingCompany\CertifactionClient\Api\Requests')
    ->toBeClasses()
    ->toImplement(Request::class)
    ->ignoring(BaseRequest::class)
    ->toExtend(BaseRequest::class)
    ->ignoring(BaseRequest::class)
    ->toBeFinal()
    ->ignoring(BaseRequest::class);

arch()
    ->expect(BaseRequest::class)
    ->toBeClass()
    ->toBeAbstract()
    ->toHaveMethod('makeRequest');

arch()
    ->expect('AmaizingCompany\CertifactionClient\Api\Responses')
    ->toBeClasses()
    ->toExtend(BaseResponse::class)
    ->ignoring(BaseResponse::class)
    ->not->toBeFinal();

arch()
    ->expect(BaseResponse::class)
    ->toBeClass()
    ->toExtend(Response::class)
    ->toHaveMethod('boot')
    ->toBeAbstract();

arch('it expects concerns to be traits')
    ->expect('AmaizingCompany\CertifactionClient\Api\Concerns')
    ->toBeTraits();

arch('it expects contracts to be interfaces')
    ->expect('AmaizingCompany\CertifactionClient\Api\Contracts')
    ->toBeInterfaces()
    ->and('AmaizingCompany\CertifactionClient\Contracts')
    ->toBeInterfaces();

arch()
    ->expect('AmaizingCompany\CertifactionClient\Api\DataObjects')
    ->toBeClasses();

arch()
    ->expect('AmaizingCompany\CertifactionClient\Exceptions')
    ->toBeClasses()
    ->toExtend(Exception::class);

arch()
    ->expect('AmaizingCompany\CertifactionClient\Facades')
    ->toBeClasses()
    ->toExtend(Facade::class);

arch()
    ->expect('AmaizingCompany\CertifactionClient\Models')
    ->toBeClasses()
    ->toExtend(Model::class)
    ->toUse([
        HasFactory::class,
        HasUlids::class,
    ]);

arch()
    ->expect('AmaizingCompany\CertifactionClient\Enums')
    ->toBeEnums();

arch('account model implements contract')
    ->expect(Account::class)
    ->toImplement(\AmaizingCompany\CertifactionClient\Contracts\Account::class);

arch('document model implements contract')
    ->expect(Document::class)
    ->toImplement(\AmaizingCompany\CertifactionClient\Contracts\Document::class);

arch('file transaction model implements contract')
    ->expect(\AmaizingCompany\CertifactionClient\Models\FileTransaction::class)
    ->toImplement(FileTransaction::class);

arch('identification model implements contract')
    ->expect(\AmaizingCompany\CertifactionClient\Models\IdentityTransaction::class)
    ->toImplement(IdentityTransaction::class);

arch('signature transaction model implements contract')
    ->expect(\AmaizingCompany\CertifactionClient\Models\SignatureTransaction::class)
    ->toImplement(SignatureTransaction::class);

arch()
    ->expect('AmaizingCompany\CertifactionClient\Jobs')
    ->toBeClasses()
    ->toImplement(ShouldQueue::class)
    ->toUse(Queueable::class)
    ->toHaveMethod('handle');

arch()
    ->expect('AmaizingCompany\CertifactionClient\Events')
    ->toBeClasses()
    ->toExtend(BaseEvent::class)
    ->ignoring(BaseEvent::class)
    ->toImplement(ShouldBroadcast::class)
    ->ignoring(BaseEvent::class)
    ->toUse(Dispatchable::class)
    ->ignoring(BaseEvent::class)
    ->toUse(InteractsWithSockets::class)
    ->ignoring(BaseEvent::class)
    ->toUse(SerializesModels::class)
    ->ignoring(BaseEvent::class);
