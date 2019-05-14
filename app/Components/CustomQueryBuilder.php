<?php

namespace App\Components;

class CustomQueryBuilder
{

    public function select($table, $columns = null):string
    {
        $columns = $columns ?? '*';
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        return 'select '. $columns .' from '. $table;
    }
}
