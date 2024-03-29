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

        $this->manager          = Manager::factory()->create();
        $this->garage           = Garage::factory()->create();
        $this->garageRepository = $this->app->make('App\Repositories\GarageRepository');
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


    public function testGetGarageComments()
    {
        $garageId = $this->garage->id;
        $result = $this->garageRepository->getCommentsById($garageId);
        $comments = Collect($result);
        $this->assertIsArray($comments->toArray());
    }
}
