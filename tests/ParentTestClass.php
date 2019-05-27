<?php
namespace Tests;

use Tests\TestCase;

class ParentTestClass extends TestCase
{
    public static function setUpBeforeClass():void
    {
        exec('php artisan migrate:refresh');
        exec('php artisan db:seed');
    }

    public function tearDown():void
    {

    }
}
