<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Product;

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
    public function testCreateProductUsingDependency($user)
    {
        $product = factory(Product::class)->create(['user_id'=> $user->id]);
        $this->assertInstanceOf(Product::class, $product);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgument()
    {
        $user = factory(User::class)->create(['abc']);
        $this->assertArrayHasKey('name', $user);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testExpectedExceptionWithoutExceptionThrow()
    {
        $user = factory(User::class)->create();
        $this->assertArrayHasKey('name', $user);
    }

    public function testErrorCounts()
    {
        $user = factory(User::class)->create(['email'=> 'abc', 'password' => 'abc23']);
        $errors = $user->getErrors();
        $this->assertCount(2, $errors);
    }

}
