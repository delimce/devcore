<?php

use App\Models\Manager\Manager;
use App\Services\ManagerService;



class ManagerApiTest extends TestCase
{
    const API_URI = "api/manager/";

    /** @var manager fake  */
    protected $manager;
    protected $managerService;

    public function setUp(): void
    {
        parent::setUp();
        $this->manager = factory(Manager::class)->create();
        $this->managerService = new ManagerService();
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
        $newToken = $this->managerService->newUserToken();
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
     */
    public function testManagerChangePasswordFailed()
    {

        $this->put(static::API_URI . "auth/password", [
            "oldpassword" => $this->manager->password,
            "password" => "newOne",
            "password_confirmation" => "newOne2",
        ], ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(400);


        $this->put(static::API_URI . "auth/password", [
            "oldpassword" => 'wrongPassword',
            "password" => "newOne",
            "password_confirmation" => "newOne",
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

        $myManager = $this->managerService->getUserByToken($this->manager->token);
        $this->put(static::API_URI . "auth/company/save", [
            "manager_id" => $myManager["id"],
            "name" => "New Company",
            "nif" => "14514211",
            "phone" => "6889874456",
        ], ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);
    }
}
