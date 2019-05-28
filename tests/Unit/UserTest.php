<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class UserTest extends TestCase
{
    public function testCreateUserWithFactory()
    {
        $user = factory(User::class)->create();
        $this->assertInstanceOf(User::class, $user);
        return $user;
    }

    public function testCreateUserWithInvalidEmail()
    {
        $user = factory(User::class)->create(['email'=>'abc']);
        $this->assertArrayHasKey('email', $user->getErrors());
    }

    public function testCreateUserWithInvalidNameAndPassword()
    {
        $user = factory(User::class)->create(['name'=> 'Sunny', 'password' => 'abc123']);
        $user = factory(User::class)->create(['name'=> $user->name, 'password' => 'abc23']);
        $errors = $user->getErrors();
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('password', $errors);
    }

    /**
     * @depends testCreateUserWithFactory
     */
    public function testCreateUniqueNameUsingDependency($user)
    {
        $user = factory(User::class)->create(['name'=> $user->name, 'password' => 'abc23']);
        $errors = $user->getErrors();
        $this->assertArrayHasKey('name', $errors);
    }
}
