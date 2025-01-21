<?php

namespace AmaizingCompany\CertifactionClient\Enums;

enum SignatureTransactionStatus: string
{
    case INTENDED = 'intended';
    case PENDING = 'pending';
    case SUCCEED = 'succeed';
    case FAILED = 'failed';
}
