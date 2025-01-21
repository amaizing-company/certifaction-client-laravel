<?php

namespace AmaizingCompany\CertifactionClient\Enums;

enum DocumentStatus: string
{
    case NOT_PREPARED = 'not_prepared';
    case PREPARED = 'prepared';
    case SIGNED = 'signed';
    case SIGNATURE_FAILED = 'signature_failed';
}
