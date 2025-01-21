<?php

namespace AmaizingCompany\CertifactionClient\Enums;

enum DocumentPrepareScope: string
{
    case REGISTER = 'register';
    case SIGN = 'sign';
    case CERTIFY = 'certify';
    case REVOKE = 'revoke';
    case RETRACT = 'retract';
}
