<?php

namespace App\Http\Controllers\Web;

use App\Services\ManagerService;
use Laravel\Lumen\Routing\Controller as BaseController;

class LandingController extends BaseController
{
    protected $manager;

    public function __construct(ManagerService $user)
    {
        $this->manager = $user;
    }

    public function managerHome()
    {
        return view('website.manager');
    }

    public function managerSignUp()
    {
        return view('website.msignup');
    }

    public function managerActivate($token)
    {
        $result = $this->manager->activateUser($token);
        if ($result) {
            return view('website.mactivate');
        }

        return response(view('errors.403'), 403);
    }
}
