<?php

namespace App\Http\Controllers\Web;

use App\Repositories\ManagerRepository;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class LandingController extends BaseController
{
    protected $manager;

    public function __construct(ManagerRepository $user)
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
        $code = trim($token);
        $result = $this->manager->activateUser($code);
        if ($result) {
            return redirect()->route('activated', ['username' => $result->fullname()]);
        }
        return response(view('errors.403'), 403);
    }

    public function managerActivated(Request $req)
    {
        return view('website.mactivated', ['username' => $req->username]);
    }
}
