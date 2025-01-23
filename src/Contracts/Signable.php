<?php

namespace AmaizingCompany\CertifactionClient\Contracts;

interface Signable
{
    public function getFileContents(): string;
}
