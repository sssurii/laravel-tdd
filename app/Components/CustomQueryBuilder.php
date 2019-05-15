<?php

namespace App\Components;

class CustomQueryBuilder
{
    private $columns;

    private $order;

    public function select($table, $columns = null, $order = null):string
    {
        $this->setOrder($order);
        $this->setColumns($columns);
        $query =  'select '. $this->columns .' from '. $table;
        if(!empty($this->order)) {
            $query .= ' order by '. $this->order;
        }

        return $query;
    }

    private function setColumns($columns)
    {
        $columns = $columns ?? '*';
        if(is_array($columns)) {
            foreach ($columns as $value) {
                if(is_array($value)) {
                    $this->setOrder($columns);
                    return $this->columns = '*';
                }
            }
            return $this->columns = implode(', ', $columns);
        }
        return $this->columns = $columns;
    }

    private function setOrder($order)
    {
        $order = $order ?? '';
        $multi_order = false;
        if(is_array($order)) {
            foreach ($order as $key => $value) {
                if(is_array($value)) {
                    $multi_order = true;
                    $order[$key] = implode(' ', $value);
                }
            }
            return $this->order = $multi_order ? implode(', ', $order) : implode(' ', $order);
        }
        return $this->order = $order;
    }
}
