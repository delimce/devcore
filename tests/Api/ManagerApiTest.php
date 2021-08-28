<?php

namespace Tests\Api;

use Tests\TestCase;
use App\Models\Manager\Manager;

class ManagerApiTest extends TestCase
{
    const API_URI = "api/manager/";

    /** @var manager fake  */
    protected $manager;
    protected $managerRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->manager = factory(Manager::class)->create();
        $this->managerRepository = $this->app->make('App\Repositories\ManagerRepository');
    }


    /** @test
     * manager index page
     */
    public function testManagerIndex()
    {
        $response = $this->call('GET', '/manager');
        $this->assertEquals(200, $response->status());
    }

    /** @test
     * simple check email
     */
    public function testManagerCheckEmailSuccess()
    {
        $email = $this->manager->email;
        $this->json('GET', static::API_URI . "check/$email", [])
            ->seeJsonEquals([
                'status' => "ok",
                "info" => true
            ]);
    }

    /** @test
     * login attemp failed structure
     */
    public function testManagerLoginFailed()
    {
        $credentials = [
            "email" => $this->manager->email,
            "password" => $this->manager->password
        ];
        $this->post(static::API_URI . "login", $credentials, []);
        $this->seeStatusCode(400);
        $this->seeJsonStructure([
            "status",
            "info" => ["message"]
        ]);
    }

    /**
     *  @test
     */
    public function testManagerGetUserByToken()
    {

        // token gen validation
        $newToken = $this->managerRepository->newUserToken();
        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        $this->assertTrue(preg_match($UUIDv4, $newToken)===1);

        $token = "badToken";
        $this->get(static::API_URI . "auth", ["Authorization" => $token]);
        $this->seeStatusCode(401);

        $this->get(static::API_URI . "auth", ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);
    }

    /**
     *  @test
     * testManagerChangePassword
     *
     * @return void
     */
    public function testManagerChangePassword()
    {

        $this->put(static::API_URI . "auth/password", [
            "oldpassword" => $this->manager->password,
            "password" => "newOneHere",
            "password_confirmation" => "newOne2Here",
        ], ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(400);


        $this->put(static::API_URI . "auth/password", [
            "oldpassword" => 'wrongPassword',
            "password" => "newOneHere",
            "password_confirmation" => "newOneHere",
        ], ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(401);


        $this->put(static::API_URI . "auth/password", [
            "oldpassword" => 'customPassword',
            "password" => "validPassword",
            "password_confirmation" => "validPassword",
        ], ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);
    }

    /**
     *  @test
     * create or update manager company
     */
    public function testManagerSaveCompany()
    {

        $this->put(static::API_URI . "auth/company/save", [
            "manager_id" => 1,
            "name" => "new company",
            "nif" => "oneNif",
            "phone" => "onePhone",
        ], ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(400);

        $myManager = $this->managerRepository->getUserByToken($this->manager->token);
        $this->put(static::API_URI . "auth/company/save", [
            "manager_id" => $myManager->get("id"),
            "name" => "New Company",
            "nif" => "14514211",
            "phone" => "6889874456",
        ], ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);
    }

    public function testSupportRequests()
    {
        $this->post(static::API_URI . "support/request", ["garage" => null, "type" => 0, "description"=>""], ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(400);

        $this->post(static::API_URI . "support/request", ["garage" => null, "type" => 1, "description"=>"some description"], ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);

    }
}
