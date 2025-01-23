<?php

namespace AmaizingCompany\CertifactionClient\Support;

class DatabaseHelper
{
    public static function getTableName(string $name): string
    {
        return config('certifaction-client-laravel.database.table_prefix').$name;
    }
}
