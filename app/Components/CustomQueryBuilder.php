<?php

namespace App\Components;

class CustomQueryBuilder
{

    public function select($table):string
    {
        return 'select * from '. $table;
    }
}
