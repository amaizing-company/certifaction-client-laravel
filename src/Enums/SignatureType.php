<?php

namespace AmaizingCompany\CertifactionClient\Enums;

enum SignatureType: string
{
    case AES = 'AES';
    case PES = 'PES';
    case QES = 'QES';
    case SES = 'SES';
}
