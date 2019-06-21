<?php

namespace Tests\Feature;

use Tests\ParentTestClass;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserFeatureTest extends ParentTestClass
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testBaseURL()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Hello Ucreate');
    }
}
