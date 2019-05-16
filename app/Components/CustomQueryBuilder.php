<?php

namespace App\Components;

class CustomQueryBuilder
{
    private $columns;

    private $order;

    private $limit;

    private $offset;

    private $capital_keywords = false;

    public function select($table, $columns = null, $order = null):string
    {
        $this->setOrder($order);
        $this->setColumns($columns);
        $query =  'select '. $this->columns .' from '. $table;
        if(!empty($this->order)) {
            $query .= ' order by '. $this->order;
        }

        if(!empty($this->limit)) {
            $query .= ' limit '. $this->limit;
        }

        if(!empty($this->offset)) {
            $query .= ' offset '. $this->offset;
        }

        return $this->checkForCapitalKeywords($query);
    }

    private function setColumns($columns)
    {
        $columns = $columns ?? '*';
        if(is_array($columns)) {
            foreach ($columns as $value) {
                if(is_integer($value)) {
                    $this->setLimitOffset($columns);
                    return $this->columns = '*';
                }
                if(is_array($value)) {
                    $this->setOrder($columns);
                    return $this->columns = '*';
                }
            }
            return $this->columns = implode(', ', $columns);
        }

        if(is_integer($columns)) {
            $this->setLimit($columns);
            return $this->columns = '*';
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
                    if(array_search("DESC", $value, true) || array_search("ASC", $value, true)) {
                        $this->capital_keywords = true;
                    }
                }
                if(in_array($value, ['DESC', 'ASC'], true)) {
                        $this->capital_keywords = true;
                }
            }
            return $this->order = $multi_order ? implode(', ', $order) : implode(' ', $order);
        }
        return $this->order = $order;
    }

    private function checkForCapitalKeywords($query)
    {
        $capital_keywords = [
            'select' => 'SELECT',
            'from' =>'FROM',
            'order by' => 'ORDER BY',
            'limit' => 'LIMIT',
            'offset' => 'OFFSET'
        ];
        if($this->capital_keywords) {
            foreach ($capital_keywords as $small => $capital) {
               $query = str_replace($small, $capital, $query);
            }
        }
        return $query;
    }

    private function setLimit($limit)
    {
        $this->limit = $limit;
    }

    private function setOffset($offset)
    {
        $this->offset = $offset;
    }

    private function setLimitOffset($limit_offset)
    {
        $this->setLimit($limit_offset[0]);
        $this->setOffset($limit_offset[1]);
    }

}
