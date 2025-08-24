<?php

namespace App\Traits;

trait PostgreSQLBooleanSupport
{
    public function scopeWhereBoolean($query, $column, $value)
    {
        return $query->whereRaw("$column = " . ($value ? 'true' : 'false'));
    }
}