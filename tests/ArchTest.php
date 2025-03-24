<?php

use AmaizingCompany\CertifactionClient\Api\Contracts\CertifactionResponse;
use AmaizingCompany\CertifactionClient\Api\Contracts\Request;
use AmaizingCompany\CertifactionClient\Api\Requests\BaseRequest;
use AmaizingCompany\CertifactionClient\Api\Responses\BaseResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Facade;

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch()
    ->expect('AmaizingCompany\CertifactionClient\Api\Requests')
    ->ignoring(BaseRequest::class)
    ->toBeClasses()
    ->toImplement(Request::class)
    ->toExtend(BaseRequest::class)
    ->toBeFinal();

arch()
    ->expect('AmaizingCompany\CertifactionClient\Api\Responses')
    ->toBeClasses()
    ->toExtend(BaseResponse::class)
    ->ignoring(BaseResponse::class)
    ->toImplement(CertifactionResponse::class)
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
    ->toExtend(Model::class);

arch()
    ->expect('AmaizingCompany\CertifactionClient\Enums')
    ->toBeEnums();
