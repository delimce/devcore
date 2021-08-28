<?php

namespace Tests\Repositories;

use Tests\TestCase;
use App\Models\Manager\Garage;
use App\Repositories\GarageServiceRepository;
use Tests\Objects\SearchObjects;


class GarageServiceRepositoryTest extends TestCase
{

    protected $garage;
    protected $garageRepository;
    protected $garageServiceRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->garage = factory(Garage::class)->create();
        $this->garageRepository = $this->app->make('App\Repositories\GarageRepository');
        $this->garageServiceRepository = $this->app->make('App\Repositories\GarageServiceRepository');
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
        $result = $this->garageServiceRepository->getGaragePoolBySegment($garageId, $segment);

        foreach (GarageServiceRepository::SERVICES_TYPES as $type) {
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
        $this->garageServiceRepository->saveGaragePool($garageId, $segment, $fakePool);
        $services = $this->garageServiceRepository->getServiceList($garageId);
        $this->assertCount(2, $services);
    }

     /**
     * @test
     * testFindService
     *
     * @return void
     */
    public function testFindService()
    {
        $criteria = [];

        # all active service
        $services = $this->garageServiceRepository->findService($criteria);
        $this->assertTrue($services->count() > 10);
        # find by type
        $criteria["type"] = "FILTER";
        $services = $this->garageServiceRepository->findService($criteria);
        $this->assertTrue($services->count() > 2);
        # find by segment
        $criteria["segment"] = "CAR";
        $services = $this->garageServiceRepository->findService($criteria);
        $this->assertTrue($services->count() > 0);
        # find by segment with except
        $criteria["segment"] = "MOTORCYCLE";
        $criteria["type"] = "OTHER";
        $services = $this->garageServiceRepository->findService($criteria);
        $this->assertTrue($services->count() === 6); # without except segment 
    }

     /**
     * @test
     * testAdvancedSearch
     *
     * @return void
     */
    public function testAdvancedSearch()
    {
        $trait =  $this->getObjectForTrait(SearchObjects::class);
        $filters = $trait->searchFilters;
        $filters["type"] = "OIL";
        $filters["segment"] = "CAR";
        $filters["service"] = 1;
        $fakePool["oil"][] =
            [
                "id" => 1,
                "type" => "OIL",
                "category" => "PREMIUM",
                "brand" => 1,
                "price" => 10,
                "select" => true
            ];
        $this->garageServiceRepository->saveGaragePool($this->garage->id, "CAR", $fakePool);
        $result =  $this->garageRepository->search($filters);
        $this->assertTrue($result->count() > 0);
    }


}