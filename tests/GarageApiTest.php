<?php

use App\Models\Manager\Manager;
use App\Services\ManagerService;
use App\Services\GarageService;

class GarageApiTest extends TestCase
{

    const API_URI = "api/manager/garage";

    /** @var manager fake  */
    protected $manager;
    protected $managerService;
    protected $garageService;

    public function setUp(): void
    {
        parent::setUp();
        $this->manager = factory(Manager::class)->create();
        $this->managerService = new ManagerService();
        $this->garageService = new GarageService();
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

    public function testGarageSchedule()
    {

        $garage = [
            "name" => "my Garage test",
            "phone" => "8561323",
            "manager" => $this->manager->id,
            "address" => "madrid, calle 123",
            "desc" => "some description",
            "country_id" => 204, //spain
            "state_id" => 0,
            "province_id" => 0,
            "zipcode" => 28027
        ];
        $garageId = $this->garageService->saveGarage($garage);

        $schedule = [
            ["garage_id" => $garageId, "am1" => "08:00", "am2" => "12:30", "pm1" => "14:00", "pm2" => "19:00"],
            ["garage_id" => $garageId, "am1" => "08:00", "am2" => "12:30", "pm1" => "14:00", "pm2" => "19:00"],
            ["garage_id" => $garageId, "am1" => "08:00", "am2" => "12:30", "pm1" => "14:00", "pm2" => "19:00"],
            ["garage_id" => $garageId, "am1" => "08:00", "am2" => "12:30", "pm1" => "14:00", "pm2" => "19:00"],
            ["garage_id" => $garageId, "am1" => "08:00", "am2" => "12:30", "pm1" => "14:00", "pm2" => "16:00"],
        ];

        $this->post(static::API_URI . "/schedule", ["garage" => $garageId, "schedule" => $schedule], ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(400);

        $schedule2 = array_merge($schedule, [
            ["garage_id" => $garageId, "am1" => "08:00", "am2" => "12:30", "pm1" => "14:00", "pm2" => "19:00"],
            ["garage_id" => $garageId, "am1" => "", "am2" => "", "pm1" => "", "pm2" => ""],
        ]);

        $this->post(static::API_URI . "/schedule", ["garage" => $garageId, "schedule" => $schedule2], ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);

        $this->get(static::API_URI . "/schedule", ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);

    }
}
