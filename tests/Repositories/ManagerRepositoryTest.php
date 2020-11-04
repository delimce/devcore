<?php

use App\Models\Manager\Manager;

class ManagerRepositoryTest extends TestCase
{
    protected $manager;
    protected $managerRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->manager = factory(Manager::class)->create();
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

    public function testGetManagerByToken()
    {
        $manager = $this->managerRepository->getUserByToken("badToken");
        $this->assertFalse($manager);
        $manager = $this->managerRepository->getUserByToken($this->manager->token);
        $this->assertArrayHasKey("id",$manager->toArray());

    }



    /**
     * @test
     * testLogin
     *
     * @return void
     */
    public function testLogin()
    {
        $credential["email"] = $this->manager->email;
        $credential["password"] = 'customPassword';
        $user = $this->managerRepository->login($credential);
        $manager = $this->managerRepository->getUserByToken($user['token']);
        $user = $this->managerRepository->getById($manager->get("id"));
        # manager has access
        $this->assertTrue($user->access()->count() > 0);
      
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

        $result = $this->managerRepository->updatePassword($token, $newPassword);
        $this->assertTrue($result);

        $newPassword2 = 'someNewPassword2';

        $result = $this->managerRepository->changePassword($token, $newPassword, $newPassword);
        $this->assertTrue($result["ok"]);

        $newPassword3 = 'someNewPassword3';

        $this->managerRepository->changePasswordWithToken($token, $newPassword3);
        $newToken = $this->managerRepository->getTokenById($id);
        $this->assertNotEquals($token, $newToken);
    }
}
