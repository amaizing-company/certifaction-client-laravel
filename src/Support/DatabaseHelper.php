<?php

namespace AmaizingCompany\CertifactionClient\Support;

class DatabaseHelper
{
    /**
     * Get the table name for package database tables.
     *
     * @param string $name Table name
     * @return string
     */
    public static function getTableName(string $name): string
    {
        return config('certifaction-client-laravel.database.table_prefix').$name;
    }
}
