<?php
namespace Tests\Repositories;

use Tests\TestCase;
use App\Models\Manager\Manager;

class ManagerRepositoryTest extends TestCase
{
    protected $manager;
    protected $managerRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->manager = Manager::factory()->create();
        $this->managerRepository = $this->app->make('App\Repositories\ManagerRepository');
    }


    /**
     * @test
     * testGetManagerById
     *
     * @return void
     */
    public function testGetManagerById()
    {
        $managerId =  $this->manager->id;
        $managerData = $this->managerRepository->getById($managerId);
        $this->assertNotNull($managerData);
    }

    /**
     * @test
     * testGetTokenById
     *
     * @return void
     */
    public function testGetTokenById()
    {
        $managerId =  $this->manager->id;
        $token = $this->managerRepository->getTokenById($managerId);
        $this->assertEquals($token, $this->manager->token);
    }

    public function testUpdatePassword()
    {
        $id = $this->manager->id;
        $token = $this->manager->token;
        $newPassword = 'someNewPassword';

        $result = $this->managerRepository->updatePassword($token, $newPassword);
        $this->assertTrue($result);
    }
  
}
