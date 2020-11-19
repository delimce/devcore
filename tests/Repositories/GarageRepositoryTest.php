<?php

use App\Models\Manager\Garage;
use App\Models\Manager\Manager;
use App\Models\Manager\Segment;
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
     * testGetGaragePoolBySegment
     *
     * @return void
     */
    public function testGetGaragePoolBySegment()
    {
        $garageId = $this->garage->id;
        $segment = "CAR";
        $result = $this->garageRepository->getGaragePoolBySegment($garageId, $segment);

        foreach (GarageRepository::TYPES as $type) {
            $index = strtolower($type);
            $this->assertArrayHasKey($index, $result);
        }
    }



    /**
     * @test
     * testSaveGaragePoolBySegment
     *
     * @param  mixed $pool
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
        $filters["city"] = 23;
        $filters["zip"] = "";
        $result =  $this->garageRepository->search($filters);
        $this->assertCount(0, $result->toArray());
    }
}
