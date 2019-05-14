<?php

namespace Tests\Unit;

use Tests\ParentTestClass;

class SampleTest extends ParentTestClass
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
        $this->assertFalse(false);
        $this->assertEquals(10, 10);
    }
}
