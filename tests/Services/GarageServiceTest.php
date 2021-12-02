<?php

namespace Tests\Services;

use App\Models\Manager\Garage;
use App\Models\Manager\Manager;
use Tests\TestCase;

class GarageServiceTest extends TestCase
{

    protected $manager;
    protected $garage;
    protected $garageService;
    protected $garageRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->manager          = factory(Manager::class)->create();
        $this->garage           = factory(Garage::class)->create();
        $this->garageService    = $this->app->make('App\Services\Garage\GarageOperationService');
        $this->garageRepository = $this->app->make('App\Repositories\GarageRepository');
    }

    /**
     * @test
     */
    public function testGetGarageScheduleTest()
    {
        $garageId = $this->garage->id;
        $result = $this->garageService->getScheduleById($garageId);
        $this->assertArrayHasKey("garage", $result);
        $this->assertArrayHasKey("schedule", $result);
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

        $garageId = $this->garageService->saveGarage($newGarage);
        $this->assertTrue($garageId > 0);
    }



}
