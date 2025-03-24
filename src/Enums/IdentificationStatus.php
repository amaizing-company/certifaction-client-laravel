<?php

namespace AmaizingCompany\CertifactionClient\Enums;

enum IdentificationStatus: string
{
    case INTENT = 'intent';
    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case FAILED = 'failed';
}
