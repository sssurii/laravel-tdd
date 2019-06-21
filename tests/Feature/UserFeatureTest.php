<?php

namespace Tests\Feature;

use Tests\ParentTestClass;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;

class UserFeatureTest extends ParentTestClass
{

    public function setUp():void
    {
        parent::setUp();
        $this->faker = Faker::create();
    }

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

    public function testRegisterFormHeading()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Register');
    }

    public function testRegisterUserSaved()
    {
        $user_data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => str_random(8),
        ];
        $response = $this->post('/register', $user_data);
        $response->assertStatus(201);
        $response->assertSee('Successful registration');
    }
}
