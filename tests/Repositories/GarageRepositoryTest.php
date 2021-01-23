<?php

use App\Models\Manager\Garage;
use App\Models\Manager\Manager;
use App\Repositories\GarageRepository;

class GarageRepositoryTest extends TestCase
{
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
     * getGarageScheduleTest
     */
    public function testGetGarageScheduleTest()
    {
        $garageId = $this->garage->id;
        $result = $this->garageRepository->getScheduleById($garageId);
        $this->assertArrayHasKey("garage", $result);
        $this->assertArrayHasKey("schedule", $result);
    }


    /**
     * @test
     * testGetGarageById
     *
     * @return void
     */
    public function testGetGarageById()
    {
        $id = $this->garage->id;
        $garage = $this->garageRepository->getById($id);
        $this->assertNotNull($garage);
    }


    /**
     * @test
     * testGetGarageDetailsById
     *
     * @return void
     */
    public function testGetGarageDetailsById()
    {
        $id = $this->garage->id;
        $garage = $this->garageRepository->getDetailsById($id);
        $this->assertNotNull($garage);
    }


    /**
     * @test
     * testGetGarageByUrl
     *
     * @return void
     */
    public function testGetGarageByUrl()
    {
        $url = $this->garage->url;
        $garage = $this->garageRepository->getByUrl($url);
        $this->assertEquals($url,$garage->url);

        $garage = $this->garageRepository->getByUrl('not-exist');
        $this->assertNull($garage);
    }



    /**
     * @test
     * testGarageSave
     *
     * @return void
     */
    public function testGarageSave()
    {
        $newGarage = [];
        $newGarage["manager"] = $this->manager->id;
        $newGarage["name"] = $this->garage->name;
        $newGarage["phone"] = $this->garage->phone;
        $newGarage["address"] = $this->garage->address;
        $newGarage["desc"] = $this->garage->desc;
        $newGarage["country_id"] = $this->garage->country_id;
        $newGarage["state_id"] = $this->garage->state_id;
        $newGarage["province_id"] = $this->garage->province_id;
        $newGarage["zipcode"] = $this->garage->zipcode;

        $garageId = $this->garageRepository->saveGarage($newGarage);
        $this->assertTrue($garageId>0);

    }



    /**
     * @test
     * testGetGaragePoolBySegment
     *
     * @return void
     */
    public function testGetGaragePoolBySegment()
    {
        $garageId = $this->garage->id;
        $segment = "CAR";
        $result = $this->garageRepository->getGaragePoolBySegment($garageId, $segment);

        foreach (GarageRepository::SERVICES_TYPES as $type) {
            $index = strtolower($type);
            $this->assertArrayHasKey($index, $result);
        }
    }


    /**
     * @test
     * testSaveGaragePoolBySegment
     *
     * @return void
     */
    public function testSaveGaragePoolBySegment()
    {
        $garageId = $this->garage->id;
        $segment = "CAR";
        $fakePool["oil"][] =
            [
                "id" => 1,
                "type" => "OIL",
                "category" => "PREMIUM",
                "brand" => 1,
                "price" => 10,
                "select" => true
            ];

        $fakePool["tyre"][] =
            [
                "id" => 28,
                "type" => "TYRE",
                "category" => "PREMIUM",
                "brand" => 29,
                "price" => 80,
                "select" => true
            ];
        $this->garageRepository->saveGaragePool($garageId, $segment, $fakePool);
        $services = $this->garageRepository->getServiceList($garageId);
        $this->assertCount(2, $services);
    }


    /**
     * @test
     * testGarageMainSearch
     *
     * @return void
     */
    public function testGarageMainSearch()
    {
        $filters["text"] = "";
        $filters["city"] = 28;
        $filters["zip"] = 28027;
        $result =  $this->garageRepository->search($filters);
        $this->assertTrue($result->count() > 0);
        foreach ($result as $item) {
            $this->assertTrue($item->state->id == 13);
            $this->assertTrue($item->province->id == 28);
            $this->assertTrue($item->province->url == "madrid");
        }

        # search by name or desc
        $garage = $result->find($this->garage->id);
        $nameSearch  = substr($garage->name, 1, 5);
        $descSearch  = substr($garage->desc, 2, 10);
        #name
        $filters["text"] = $nameSearch;
        $result2 =  $this->garageRepository->search($filters);
        $this->assertStringContainsString($nameSearch,$result2->first()->name);
        #desc
        $filters["text"] = $descSearch;
        $result3 =  $this->garageRepository->search($filters);
        $this->assertStringContainsString($descSearch,$result3->first()->desc);

    }
}
