<?php

namespace AmaizingCompany\CertifactionClient\Enums;

enum CertifactionLocalEndpoint: string
{
    case ACCOUNT_QES_STATUS_CHECK = '/qes/check';
    case SIGNATURE_REQUEST_ADD_DOCUMENTS = '/request/file/add';
    case SIGNATURE_REQUEST_CANCEL_ALL = '/request/cancel/all';
    case SIGNATURE_REQUEST_CANCEL_SIGNERS = '/request/cancel';
}
