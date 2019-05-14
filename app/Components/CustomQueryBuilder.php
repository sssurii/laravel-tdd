<?php

namespace App\Components;

class CustomQueryBuilder
{

    public function select($table, $columns = null, $order = null):string
    {
        $columns = $columns ?? '*';
        $order = $order ?? '';
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $order = is_array($order) ? implode(' ', $order) : $order;
        $query =  'select '. $columns .' from '. $table;
        if(!empty($order)) {
            $query .= ' order by '. $order;
        }

        return $query;
    }
}
