<?php

namespace App\Http\Controllers\Web;

use App\Repositories\GarageRepository;
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

    public function index()
    {
        return view('website.index');
    }


    public function garageDetail(
        GarageRepository $garageRepository,
        $province,
        $url
    ) {
        $garage = $garageRepository->getByUrl($url);
        if (!$garage) {
            return response(view('errors.404'), 404);
        }
        return view('website.garage', ['id' => $garage->id, "name" => $garage->name]);
    }

    /**
     * MANAGER ROUTES
     */

    public function managerHome()
    {
        return view('website.manager');
    }

    public function managerSignUp()
    {
        return view('website.msignup');
    }

    public function managerReset($token)
    {
        $code = trim($token);
        $user = $this->manager->getUserByToken($code);
        if ($user) {
            return view('website.reset', ['token' => $code]);
        }
        return response(view('errors.403'), 403);
    }



    public function managerActivate($token)
    {
        $code = trim($token);
        $result = $this->manager->activateUser($code);
        if ($result) {
            return redirect()->route('activated', ['username' => $result->fullname]);
        }
        return response(view('errors.403'), 403);
    }

    public function managerActivated(Request $req)
    {
        return view('website.mactivated', ['username' => $req->username]);
    }
}
