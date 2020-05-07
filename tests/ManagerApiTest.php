<?php

class ManagerApiTest extends TestCase
{
    const API_URI = "api/manager/";

    /**
     * manager index page
     */
    public function testManagerIndex()
    {
        $response = $this->call('GET', '/manager');
        $this->assertEquals(200, $response->status());
    }

    /**
     * simple check email
     */
    public function testManagerCheckEmailSuccess()
    {
        $email = env('TDD_EMAIL', 'delimce@gmail.com');
        $this->json('GET', static::API_URI . "check/$email", [])
            ->seeJsonEquals([
                'status' => "ok",
                "info" => true
            ]);
    }

    /**
     * login attemp failed structure
     */
    public function testManagerLoginFailed()
    {
        $credentials = ["email" => "", "password" => ""];
        $this->post(static::API_URI . "login", $credentials, []);
        $this->seeStatusCode(400);
        $this->seeJsonStructure([
            "status",
            "info" => ["message"]
        ]);
    }
}
