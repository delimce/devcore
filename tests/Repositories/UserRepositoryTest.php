<?php
namespace Tests\Repositories;

use Tests\TestCase;
use App\Models\Users\User;

class UserRepositoryTest extends TestCase
{
    protected $user;
    protected $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->userRepository = $this->app->make('App\Repositories\UserRepository');
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
        $result = $this->userRepository->createUser($data);
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

        $result = $this->userRepository->doLogin($credentials);
        $this->assertFalse($result["ok"]);

        $activate = $this->userRepository->activateUser($this->user->id);
        if ($activate) {
            $result = $this->userRepository->doLogin($credentials);
            $this->assertTrue($result["ok"]);
        }
    }
}
