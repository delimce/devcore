<?php
namespace Tests\Repositories;

use Tests\TestCase;
use App\Models\Manager\Garage;
use App\Models\Manager\Manager;
use Tests\Objects\SearchObjects;

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
        $item = $this->garageRepository->getById($id);
        $this->assertNotNull($item);
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
        $item = $this->garageRepository->getDetailsById($id);
        $this->assertNotNull($item);
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
        $item = $this->garageRepository->getByUrl($url);
        $this->assertEquals($url, $item->url);

        $item = $this->garageRepository->getByUrl('not-exist');
        $this->assertNull($item);
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
        $this->assertTrue($garageId > 0);
    }


    /**
     * @test
     * testGarageMainSearch
     *
     * @return void
     */
    public function testGarageMainSearch()
    {
        $trait =  $this->getObjectForTrait(SearchObjects::class);
        $filters = $trait->searchFilters;
        $filters["zip"] = 28027;
        $result =  $this->garageRepository->search($filters);
        $this->assertTrue($result->count() > 0);
        foreach ($result as $item) {
            $this->assertTrue($item->state->id == 13);
            $this->assertTrue($item->province->id == 28);
            $this->assertTrue($item->province->url == "madrid");
        }

        # search by name or desc
        $item = $result->find($this->garage->id);
        $nameSearch  = substr($item->name, 1, 5);
        $descSearch  = substr($item->desc, 2, 10);
        #name
        $filters["text"] = $nameSearch;
        $result2 =  $this->garageRepository->search($filters);
        $this->assertStringContainsString($nameSearch, $result2->first()->name);
        #desc
        $filters["text"] = $descSearch;
        $result3 =  $this->garageRepository->search($filters);
        $this->assertStringContainsString($descSearch, $result3->first()->desc);
    }

}
