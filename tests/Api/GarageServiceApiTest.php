<?php

use App\Models\Manager\Manager;
use App\Models\Manager\Garage;

class GarageServiceApiTest extends TestCase
{
    const API_URI = "api/manager/garage/services";

    /** @var manager fake  */
    protected $manager;
    protected $garage;
    protected $garageRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->manager = factory(Manager::class)->create();
        $this->garage = factory(Garage::class)->create();
        $this->garageRepository = $this->app->make('App\Repositories\GarageRepository');
    }

    /**
     * @test
     * testGarageServiceSelects
     *
     * @return void
     */
    public function testGarageServiceSelects()
    {

        $this->get(static::API_URI . "/types", ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);

        $this->get(static::API_URI . "/categories", ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);

        $filter = "type=TYRE";
        $response = $this->call('GET', static::API_URI .  "/brands/?" . $filter, [], [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);

        $this->assertEquals(200, $response->status());
        $this->assertGreaterThan(2, $this->getCountOfResponseList($response));

        $filter = "segment=CAR&type=TYRE";
        $response = $this->call('GET', static::API_URI .  "/catalog/?" . $filter, [], [], [], [
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
        $this->post(static::API_URI, $fakeGarageServiceData, ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(400);

        $fakeGarageServiceData = array_merge($fakeGarageServiceData, [
            "service_id" => 1,
            "price" => 5.5
        ]);

        // good
        $response = $this->call("POST", static::API_URI, $fakeGarageServiceData, [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);
        $this->assertEquals(200, $response->status());

        // get garage service just created
        $garageService = $this->getArrayByResponse($response);
        $response2 = $this->call('GET', static::API_URI .  "/id/" . $garageService["id"], [], [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);

        $this->assertArrayHasKey("service", $this->getArrayByResponse($response2));


        $this->get(static::API_URI . "/" . $garageId . "/list", ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);

        // get list of garage services
        $response3 = $this->call("GET", static::API_URI . "/" . $garageId . "/list", [], [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);

        $this->assertEquals(200, $response3->status());
        $this->assertEquals(1, $this->getCountOfResponseList($response3));

        //get list
        $items = $this->getArrayByResponse($response3)["list"];
        $this->assertArrayHasKey("service", $items[0]);

        // delete garage service
        $this->call("DELETE", static::API_URI, ["service_id" => $garageService["id"]], [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);
        $this->seeStatusCode(200);
    }


    /**
     * @test
     * testGetPoolBySegment
     *
     * @return void
     */
    public function testGetPoolBySegment()
    {
        $garageId =  $this->garage->id;
        $segment = "CAR";
        $URL = static::API_URI . "/pool/" . $garageId . "/" . $segment;

        $this->get($URL, ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(200);


        $response2 = $this->call('GET', $URL, [], [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);

        $items = $this->getArrayByResponse($response2);
        // dd($items);
        $this->assertCount(1, $items['workforce']);
        $this->assertCount(4, $items['check']);
    }


    /**
     * @test
     * testSavePoolBySegment
     *
     * @return void
     */
    public function testSavePoolBySegment()
    {

        $garageId =  $this->garage->id;
        $fakeGarageServiceData =  [
            'garage_id' => $garageId,
            'segment' => 'CAR',
            'pool' => []
        ];

        $URL = static::API_URI . "/pool";

        // create garage pool fails
        $this->post($URL, $fakeGarageServiceData, ["Authorization" => $this->manager->token]);
        $this->seeStatusCode(400);

        $fakeGarageServiceData['pool']["workforce"][] = [
            "select" => true,
            "id" => 27,
            "brand" => "",
            "category" => "",
            "price" => 10
        ];

        // good
        $response = $this->call("POST", $URL, $fakeGarageServiceData, [], [], [
            "HTTP_Authorization" => $this->manager->token,
        ]);
        $this->assertEquals(200, $response->status());
    }
}
