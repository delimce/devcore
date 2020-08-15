<?php
use App\Models\Manager\Manager;

class ManagerRepositoryTest extends TestCase
{
    protected $garageRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->manager = factory(Manager::class)->create();
        $this->managerRepository = $this->app->make('App\Repositories\ManagerRepository');
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
        $this->assertEquals($token,$this->manager->token);
    }
        
    /**
     * @test
     * testChangePassword
     *
     * @return void
     */
    public function testChangePassword()
    {
        $id = $this->manager->id;
        $token = $this->manager->token;
        $newPassword = 'someNewPassword';

        $result = $this->managerRepository->updatePassword($token,$newPassword);
        $this->assertTrue($result);

        $newPassword2 = 'someNewPassword2';

        $result = $this->managerRepository->changePassword($token,$newPassword,$newPassword);
        $this->assertTrue($result["ok"]);

        $newPassword3 = 'someNewPassword3';

        $this->managerRepository->changePasswordWithToken($token,$newPassword3);
        $newToken = $this->managerRepository->getTokenById($id);
        $this->assertNotEquals($token,$newToken);

    }

}