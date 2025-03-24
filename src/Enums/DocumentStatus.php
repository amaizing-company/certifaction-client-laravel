<?php

namespace AmaizingCompany\CertifactionClient\Enums;

enum DocumentStatus: string
{
    case INTENT = 'intent';
    case PREPARED = 'prepared';
    case SIGNED = 'signed';
    case SIGNATURE_FAILED = 'signature_failed';
}
