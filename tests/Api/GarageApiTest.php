<?php

use App\Models\Manager\Manager;
use App\Models\Manager\Garage;

class GarageApiTest extends TestCase
{

    const API_URI = "api/manager/garage";

    /** @var manager fake  */
    protected $manager;
    protected $garage;
    protected $garageRepository;
    protected $mediaRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->manager = factory(Manager::class)->create();
        $this->garage = factory(Garage::class)->create();
        $this->garageRepository = $this->app->make('App\Repositories\GarageRepository');
        $this->mediaRepository = $this->app->make('App\Repositories\MediaRepository');
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

        $response = $this->call("POST", static::API_URI . "/", $dummy2, [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);
        $this->assertEquals(200, $response->status());

        // get garageId?
        $garageResponse = $this->getArrayByResponse($response);
        $this->assertArrayHasKey("garageId",$garageResponse);


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
        $garageId = $this->garage->id;

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

        $garageId =  $this->garage->id;

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

        $response =  $this->call('GET', static::API_URI . "/media/" . $garageId, [], [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);

        $result = json_decode($response->getContent(), true);
        $this->assertCount(1, $result["info"]);

        $media = $this->mediaRepository->getFirstMediaFileByGarageId($garageId);

        $response = $this->call('GET', 'storage/media/' . $media->path);
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



    /**
     * @test
     * testGarageServiceSelects
     *
     * @return void
     */
    public function testGarageServiceSelects()
    {

        $this->get(static::API_URI . "/services/segments", ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);

        $this->get(static::API_URI . "/services/types", ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);

        $this->get(static::API_URI . "/services/categories", ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);

        $filter = "type=TYRE";
        $response = $this->call('GET', static::API_URI .  "/services/brands/?" . $filter, [], [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);

        $this->assertEquals(200, $response->status());
        $this->assertGreaterThan(2, $this->getCountOfResponseList($response));

        $filter = "segment=CAR&type=TYRE";
        $response = $this->call('GET', static::API_URI .  "/services/catalog/?" . $filter, [], [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);

        $this->assertEquals(200, $response->status());
        $this->assertGreaterThan(2, $this->getCountOfResponseList($response));
    }


    /**
     * @test
     * testGarageServicesOperations
     *
     * @return void
     */
    public function testGarageServicesOperations()
    {
        $garageId =  $this->garage->id;
        $fakeGarageServiceData =  [
            'garage_id' => $garageId,
            'segment' => 'CAR',
            'type' => 'OIL'
        ];

        // create garage service fails
        $this->post(static::API_URI . "/services", $fakeGarageServiceData, ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(400);

        $fakeGarageServiceData = array_merge($fakeGarageServiceData, [
            "service_id" => 1,
            "price" => 5.5
        ]);

        // good
        $response = $this->call("POST", static::API_URI . "/services", $fakeGarageServiceData, [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);
        $this->assertEquals(200, $response->status());

        // get garage service just created
        $garageService = $this->getArrayByResponse($response);
        $response2 = $this->call('GET', static::API_URI .  "/services/id/" . $garageService["id"], [], [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);

        $this->assertArrayHasKey("service", $this->getArrayByResponse($response2));


        $this->get(static::API_URI . "/services/" . $garageId . "/list", ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);

        // get list of garage services
        $response3 = $this->call("GET", static::API_URI . "/services/" . $garageId . "/list", [], [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);

        $this->assertEquals(200, $response3->status());
        $this->assertEquals(1,$this->getCountOfResponseList($response3));

        //get list
        $items = $this->getArrayByResponse($response3)["list"];
        $this->assertArrayHasKey("service",$items[0]);

        // delete garage service
        $this->call("DELETE", static::API_URI . "/services", ["service_id" => $garageService["id"]], [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);
        $this->seeStatusCode(200);

    }
}
