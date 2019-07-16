<?php

namespace Tests\Feature;

use Tests\ParentTestClass;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;
use App\User;

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

    public function testRegisterUserWithInvalidEmail()
    {
        $user_data = [
            'name' => $this->faker->name,
            'email' => str_random(8),
            'password' => str_random(8),
        ];
        $response = $this->post('/register', $user_data);
        $response->assertStatus(400);
        $response->assertSee('The email must be a valid email address.');
    }

    public function testRegisterUserWithInvalidName()
    {
        $user_data = [
            'name' => null,
            'email' => str_random(8),
            'password' => str_random(8),
        ];
        $response = $this->post('/register', $user_data);
        $response->assertStatus(400);
        $response->assertSee('The name field is required.');
    }

    public function testLoginPage()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Login here');
    }

    public function testLogin()
    {
        $user = factory(User::class)->create();
        $user_data = [
            'email' => $user->email,
            'password' => $user->password,
        ];
        $response = $this->post('/login', $user_data);
        $response->assertStatus(200);
        $response->assertSee('Welcome');
    }

    public function testLoginWithInvalidEmail()
    {
        $user = factory(User::class)->create();
        $user_data = [
            'email' => str_random(8),
            'password' => $user->password,
        ];
        $response = $this->post('/login', $user_data);
        $response->assertStatus(400);
        $response->assertSee('The email must be a valid email address.');
    }

    public function testLoginWithEmptyPassword()
    {
        $user = factory(User::class)->create();
        $user_data = [
            'email' => $user->email,
            'password' => null,
        ];
        $response = $this->post('/login', $user_data);
        $response->assertStatus(400);
        $response->assertSee('The password field is required.');
    }
}
