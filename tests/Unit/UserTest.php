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
    }

    public function testCreateUserWithInvalidEmail()
    {
        $user = factory(User::class)->create(['email'=>'abc']);
        $errors = $user->getErrors();
        $this->assertArrayHasKey('email', $user->getErrors());
    }
}
