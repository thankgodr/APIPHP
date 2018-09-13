<?php

namespace App\Functional\Api\V1\Controllers;

use Hash;
use App\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RefreshControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $user = new User([
            'name' => 'Test',
            'email' => 'test@email.com',
            'password' => '123456'
        ]);

        $user->save();
    }
}
