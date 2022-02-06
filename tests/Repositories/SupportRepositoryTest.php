<?php
namespace Tests\Repositories;

use Tests\TestCase;
use App\Models\Manager\Manager;

class SupportRepositoryTest extends TestCase
{
    protected $manager;
    protected $supportRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->manager =  Manager::factory()->create();
        $this->supportRepository = $this->app->make('App\Repositories\SupportRepository');
        $this->managerRepository = $this->app->make('App\Repositories\ManagerRepository');
    }

        
    /**
     * @test
     * testManagerSupportRequest
     *
     * @return void
     */
    public function testManagerSupportRequest()
    {
        # save data...
        $data = [
            "garage_id" => null,
            "manager_id" => $this->manager->id,
            "request_type" => 2,
            "description" => "some description here...",
        ];

        $this->supportRepository->saveSupportRequest($data);

        # retrieve some data
        $manager = $this->managerRepository->getById($this->manager->id);
        $this->assertTrue($manager->supportRequests()->count()>0);
        
    }

}