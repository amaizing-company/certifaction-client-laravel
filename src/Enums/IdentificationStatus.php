<?php

namespace AmaizingCompany\CertifactionClient\Enums;

enum IdentificationStatus: string
{
    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case FAILED = 'failed';
}
