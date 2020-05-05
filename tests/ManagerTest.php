<?php

use App\Services\ManagerService as manager;

class ManagerTest extends TestCase
{

    /**
     * user token is valid
     */
    public function testManagerIsTokenValid()
    {
        $token = env('TDD_TOKEN', '');
        $this->assertTrue(manager::isTokenvalid($token));

        $token="Not valid";
        $this->assertFalse(manager::isTokenvalid($token));
    }
}
