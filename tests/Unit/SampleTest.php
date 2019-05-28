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

    /**
     * @dataProvider urlDataProvider
     */
    public function testURLRegularExpression($url, $regex, $result)
    {
        $this->assertEquals($result, preg_match($regex, $url));
    }

    public function urlDataProvider()
    {
        $regex = '/^((https?):\/\/)?(www.)?[a-z0-9-]+(\.[a-z]{2,}){1,3}(#?\/?[a-zA-Z0-9#]+)*\/?(\?[a-zA-Z0-9-_]+=[a-zA-Z0-9-%]+&?)?$/';
        return [ ['https://regex101.com/r/cO8lqs/5', $regex, true],
                 ['https://mail.google.com/mail/u/0/#inbox', $regex, true],
                 ['https://ucreate/about', $regex, false]
               ];
    }


    public function testUpperLimit()
    {
        $this->assertTrue(true);
        return 100;
    }

    /**
     * @depends testUpperLimit
     * @dataProvider sumDataProvider
     */
    public function testSumUpperLimit($number, $add, $result, $limit)
    {
        $output = ($number + $add) < $limit;
        $this->assertEquals($result, $output);
    }

    public function sumDataProvider()
    {
        return [
            [3, 24, true],
            [45, 51, true],
            [62, 85, false]
        ];
    }
}
