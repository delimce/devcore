<?php

use App\Models\Manager\Manager;
use App\Models\Manager\Garage;

class GarageApiTest extends TestCase
{

    const API_URI = "api/manager/garage";
    const API_URI_GARAGE = "api/garage";

    /** @var manager fake  */
    protected $manager;
    protected $garage;
    protected $mediaRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->manager = factory(Manager::class)->create();
        $this->garage = factory(Garage::class)->create();
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
        $this->assertArrayHasKey("garageId", $garageResponse);
    }


    /** @test
     * get garage info
     */
    public function testGarageGetInfo()
    {
        $response = $this->get(static::API_URI . "/info", ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);
    }

    public function testGarageGetSegments()
    {
        $this->get(static::API_URI . "/segments", ["Authorization" => $this->manager->token]);
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

        $endpoint = "/schedule";

        $this->post(static::API_URI . $endpoint, ["garage" => $garageId, "schedule" => $schedule], ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(400);

        $schedule2 = array_merge($schedule, [
            ["garage_id" => $garageId, "am1" => "08:00", "am2" => "12:30", "pm1" => "14:00", "pm2" => "19:00"],
            ["garage_id" => $garageId, "am1" => "", "am2" => "", "pm1" => "", "pm2" => ""],
        ]);

        $this->post(static::API_URI . $endpoint, ["garage" => $garageId, "schedule" => $schedule2], ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);

        $this->get(static::API_URI . $endpoint, ["Authorization" => $this->manager->token]);
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

        $endpoint = "/media";

        $this->call("POST", static::API_URI . $endpoint, ["garage" => $garageId], [], ["file" => $file], [
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

        $this->call("DELETE", static::API_URI . $endpoint, ["garage" => $garageId, "path" => $media->original], [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);
        $this->seeStatusCode(200);

        $this->call("DELETE", static::API_URI . $endpoint, ["garage" => $garageId, "path" => "notExists"], [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);
        $this->seeStatusCode(403);
    }


    /**
     * GARAGE FRONT METHODS
     */

    /**
     * @test
     * testGarageSearch
     *
     * @return void
     */
    public function testGarageSearch()
    {
        $filters = [
            "text" => "",
            "city" => "",
            "zip" => "",
        ];

        $endpoint = "/search?";

        $this->call("GET", static::API_URI_GARAGE . $endpoint, $filters, [], [], []);
        $this->seeStatusCode(403);

        $filters["text"] = "testing";
        $filters["city"] = 28;

        $this->call("GET", static::API_URI_GARAGE . $endpoint, $filters, [], [], []);
        $this->seeStatusCode(200);

        $filters["segment"] = "CAR";
        $filters["type"] = "TYRE";
        $filters["service"] = 1;

        $this->call("GET", static::API_URI_GARAGE . $endpoint, $filters, [], [], []);
        $this->seeStatusCode(200);
    }

    public function testSearchService()
    {
        $filters["type"] = "FILTER";
        $filters["segment"] = "CAR";

        $this->call("GET", static::API_URI_GARAGE . "/search/services?", $filters, [], [], []);
        $this->seeStatusCode(200);
    }



    /**
     * @test
     * testGetGarageById
     *
     * @return void
     */
    public function testGetGarageById()
    {
        $garageId = $this->garage->id;
        $this->call("GET", static::API_URI_GARAGE . "/details/$garageId", [], [], []);
        $this->seeStatusCode(200);
    }
}
