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

    public function testa()
    {
        $this->assertTrue(true);
        return 'a';
    }

    public function testb()
    {
        $this->assertTrue(true);
        return 'b';
    }

    /**
     * @depends testa
     * @depends testb
     */
    public function testc($a, $b)
    {
        $this->assertEquals('a', $a);
        $this->assertEquals('b', $b);
    }
}
