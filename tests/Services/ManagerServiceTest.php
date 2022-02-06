<?php

namespace Tests\Services;

use App\Models\Manager\Manager;
use Tests\TestCase;

class ManagerServiceTest extends TestCase
{

    protected $manager;
    protected $managerService;
    protected $managerRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->manager = Manager::factory()->create();
        $this->managerService = $this->app->make('App\Services\Manager\ManagerService');
        $this->managerRepository = $this->app->make('App\Repositories\ManagerRepository');
    }

    public function testLogin()
    {
        $credential["email"] = $this->manager->email;
        $credential["password"] = 'customPassword';
        $user = $this->managerService->login($credential);
        $manager = $this->managerService->getUserByToken($user['token']);
        $user = $this->managerRepository->getById($manager->get("id"));
        # manager has access
        $this->assertTrue($user->access()->count() > 0);
    }

    public function testGetManagerByToken()
    {
        $manager = $this->managerService->getUserByToken("badToken");
        $this->assertFalse($manager);
        $manager = $this->managerService->getUserByToken($this->manager->token);
        $this->assertArrayHasKey("id", $manager->toArray());
    }

    public function testChangePassword()
    {
        $id = $this->manager->id;
        $token = $this->manager->token;
        $oldPassword = "customPassword";
        $newPassword = 'someNewPassword';

        $result = $this->managerService->changePassword($token, $oldPassword, $newPassword);
        $this->assertTrue($result["ok"]);

        $newPassword3 = 'someNewPassword3';

        $this->managerService->changePasswordWithToken($token, $newPassword3);
        $newToken = $this->managerRepository->getTokenById($id);
        $this->assertNotEquals($token, $newToken);
    }
}
