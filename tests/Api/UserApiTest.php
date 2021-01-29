<?php

use App\Models\Users\User;

class UserApiTest extends TestCase
{

    const API_URI = "api/users";


    /** @var user fake  */
    protected $user;
    protected $userRepository;
    protected $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->userRepository = $this->app->make('App\Repositories\UserRepository');
        $this->faker = \Faker\Factory::create();
    }



    /**
     * @test
     * testCreateNewUser
     *
     * @return void
     */
    public function testCreateNewUser()
    {
        $data = [
            "name" => $this->user->name,
            "lastname" => $this->user->lastname,
            "email" => $this->user->email,
            "password" => ""
        ];

        # user email exists
        $this->call("POST", static::API_URI . "/create", $data, [], [], []);
        $this->seeStatusCode(400);

        #password invalid
        $data["name"] = $this->faker->name;
        $data["lastname"] = $this->faker->lastname;
        $data["email"] = $this->faker->email;
        $data["password"] = "123456";
        $this->call("POST", static::API_URI . "/create", $data, [], [], []);
        $this->seeStatusCode(400);

        #correct
        $data["password"] = $this->faker->password();
        $data["password_confirmation"] = $data["password"];
        $this->call("POST", static::API_URI . "/create", $data, [], [], []);
        $this->seeStatusCode(200);
    }


    /**
     * @test
     * testUserLoginApi
     *
     * @return void
     */
    public function testUserLoginApi()
    {
        $credentials = ["email" => $this->user->email, "password" => ""];
        $this->call("POST", static::API_URI . "/login", $credentials, [], [], []);
        $this->seeStatusCode(400);

        # wrong credentials
        $credentials["password"] = "invalidPassword";
        $this->call("POST", static::API_URI . "/login", $credentials, [], [], []);
        $this->seeStatusCode(401);

        # right credentials, but not verified
        $credentials["password"] = "customPassword";
        $this->call("POST", static::API_URI . "/login", $credentials, [], [], []);
        $this->seeStatusCode(401);

        # right credentials, and verified
        $this->userRepository->activateUser($this->user->id);
        $this->call("POST", static::API_URI . "/login", $credentials, [], [], []);
        $this->seeStatusCode(200);
    }
}
