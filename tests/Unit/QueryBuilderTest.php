<?php

namespace Tests\Unit;

use Tests\ParentTestClass;
use App\Components\CustomQueryBuilder;

class QueryBuilderTest extends ParentTestClass
{

    public function setup():void
    {

    }

    public function testSelectQuery()
    {
        $sql = new CustomQueryBuilder();
        $this->assertEquals('select * from products', $sql->select('products'));
    }

    public function testSelectSpecificColumnsQuery()
    {
        $sql = new CustomQueryBuilder();
        $this->assertEquals('select id, name from products', $sql->select('products', ['id', 'name']));
    }
}
