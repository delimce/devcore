<?php

use App\Models\Manager\Manager;
use App\Services\ManagerService;
use App\Services\GarageService;
use App\Services\MediaService;

class GarageApiTest extends TestCase
{

    const API_URI = "api/manager/garage";

    /** @var manager fake  */
    protected $manager;
    protected $managerService;
    protected $garageService;
    protected $mediaService;

    public function setUp(): void
    {
        parent::setUp();
        $this->manager = factory(Manager::class)->create();
        $this->managerService = new ManagerService();
        $this->garageService = new GarageService();
        $this->mediaService = new MediaService();
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

    /** @test 
     *  garage schedules
     */
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


    /** @test 
     *  garage media
     */
    public function testGarageMediaOperations()
    {

        $garageId = $this->garageService->saveGarage([
            "name" => "my Garage media test",
            "phone" => "8561323",
            "manager" => $this->manager->id,
            "address" => "madrid",
            "desc" => "some description",
            "country_id" => 204, //spain
            "state_id" => 0,
            "province_id" => 0,
            "zipcode" => 28027
        ]);

        $file = new \Illuminate\Http\UploadedFile(
            resource_path('assets/img/common/logo01.png'),
            'image.jpg',
            'image/jpeg',
            filesize(resource_path('assets/img/common/logo01.png')),
            null,
            true // for $test
        );

        $this->call("POST", static::API_URI . "/media", ["garage" => $garageId], [], ["file" => $file], [
            "HTTP_Authorization" => $this->manager->token,
        ]);
        $this->seeStatusCode(200);

        $response =  $this->call('GET', static::API_URI . "/media/".$garageId,[], [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);
        
        $result = json_decode($response->getContent(),true);
        $this->assertCount(1,$result["info"]);

        $media = $this->mediaService->getFirstMediaFileByGarageId($garageId);

        $response = $this->call('GET', 'storage/media/'.$media->path);
        $this->assertEquals(200, $response->status());

       
        $this->call("DELETE", static::API_URI . "/media", ["garage" => $garageId, "path" => $media->original], [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);
        $this->seeStatusCode(200);

        $this->call("DELETE", static::API_URI . "/media", ["garage" => $garageId, "path" => "notExists"], [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);
        $this->seeStatusCode(403);
    }


    public function testGarageServiceRequests()
    {

    }
    
}
