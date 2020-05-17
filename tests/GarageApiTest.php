<?php

use App\Models\Manager\Manager;
use App\Services\ManagerService;

class GarageApiTest extends TestCase
{

    const API_URI = "api/manager/garage";

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
     * garage save
     */
    public function testGarageSaveInfo()
    {
        $dummy = [
            "name" => "my Garage",
            "phone" => "8561323",
            "manager_id" => $this->manager->id,
            "address" => "madrid, calle 123",
            "desc" => "some description",

        ];

        $this->post(static::API_URI . "/", $dummy, ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(400);

        $required = [
            "country_id" => 204, //spain
            "state_id" => 0,
            "province_id" => 0,
            "zipcode" => 28027,
        ];
        $dummy2 = array_merge($dummy, $required);

        $this->post(static::API_URI . "/", $dummy2, ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);
    }


    /** @test 
     * get garage info
     */
    public function testGarageGetInfo()
    {
        $response = $this->get(static::API_URI . "/info", ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);
    }
}
