<?php

namespace AmaizingCompany\CertifactionClient\Enums;

enum FileTransactionStatus: string
{
    case INTENT = 'intent';
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case FAILURE = 'failure';
}
