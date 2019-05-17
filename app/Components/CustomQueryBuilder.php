<?php

namespace App\Components;

class CustomQueryBuilder
{
    private $table;

    private $columns;

    private $order;

    private $limit;

    private $offset;

    private $capital_keywords = false;

    private $aggregate_functions = ['count', 'max'];

    private $joins;

    public function select($table, $columns = null, $order = null):string
    {
        $this->setTable($table);
        $this->setOrder($order);
        $this->setColumns($columns);
        $query =  'select '. $this->columns .' from '. $this->table;
        if(!empty($this->order)) {
            $query .= ' order by '. $this->order;
        }

        if(!empty($this->limit)) {
            $query .= ' limit '. $this->limit;
        }

        if(!empty($this->offset)) {
            $query .= ' offset '. $this->offset;
        }

        if(!empty($this->joins)) {
            $query .= ' join '. $this->joins;
        }

        return $this->checkForCapitalKeywords($query);
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
                if(is_integer($value)) {
                    $this->setLimitOffset($columns);
                    return $this->columns = '*';
                }
                if(in_array($value, $this->aggregate_functions, true)) {
                    return $this->columns = $this->setAggregateFunctions($columns);
                }
                if($value == 'DISTINCT') {
                    return $this->columns = $value .' '. $columns[1];
                }
            }
            return $this->columns = implode(', ', $columns);
        }

        if(is_integer($columns)) {
            $this->setLimit($columns);
            return $this->columns = '*';
        }

        if($columns == 'categories') {
            $this->setJoins($this->table, $columns, $this->order);
            $this->order = null;
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
            'offset' => 'OFFSET',
            'insert into' => 'INSERT INTO',
            'values' => 'VALUES'
        ];
        if($this->capital_keywords) {
            foreach ($capital_keywords as $small => $capital) {
               $query = str_replace($small, $capital, $query);
            }
        }
        return $query;
    }

    private function setTable($table)
    {
        $this->table = $table;
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

    private function setAggregateFunctions($function)
    {
        return $function[0]. '("'.$function[1].'")';
    }

    private function setJoins($table, $join, $columns)
    {
        $columns = explode(' ', $columns);
        return $this->joins = $join.' on '.$table . '.'. $columns[1] .'='.$join.'.'.$columns[0];
    }


    public function insert($table, $columns, $values)
    {
        $this->setTable($table);

        $query = 'insert into '.$this->table.'("'.implode('", "', $columns).'") values'
        . $this->getInsertValuesString($values);
        $this->capital_keywords = true;
        return $this->checkForCapitalKeywords($query);
    }

    public function getInsertValuesString($values)
    {
        $wrap_parenthesis = true;
        $values_str = '';
        foreach ($values as $value) {
            if(is_array($value)) {
                $values_str .= $this->getInsertValuesString($value).', ';
                $wrap_parenthesis = false;
                continue;
            }
            if($value == 'DEFAULT') {
                $values_str .= $value.', ';
                continue;
            }
            if(is_integer($value)) {
                $values_str .= $value.', ';
                continue;
            }
            $values_str .= '"'.$value.'", ';
        }
        return  $wrap_parenthesis ? '('. rtrim($values_str, ', ') . ')' : rtrim($values_str, ', ');
    }

}
