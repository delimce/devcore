<?php

namespace Tests\Services;

use Tests\TestCase;
use App\Models\Users\User;

class UserServiceTest extends TestCase
{

    protected $user;
    protected $userService;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->userService = $this->app->make('App\Services\User\UserService');
    }

     /**
     * testCreateUser
     *
     * @return void
     */
    public function testCreateUser()
    {
        $data = [
            "name" => $this->user->name,
            "lastname" => $this->user->lastname,
            "email" => $this->user->email,
            "password" => $this->user->password
        ];
        $result = $this->userService->createUser($data);
        $this->assertTrue($result->id > 0);
    }

     /**
     * testUserLogin
     *
     * @return void
     */
    public function testUserLogin()
    {
        $credentials = [
            "email" => $this->user->email,
            "password" => "customPassword",
        ];

        $result = $this->userService->doLogin($credentials);
        $this->assertFalse($result["ok"]);

        $activate = $this->userService->activateUser($this->user->id);
        if ($activate) {
            $result = $this->userService->doLogin($credentials);
            $this->assertTrue($result["ok"]);
        }
    }




}
