<?php

namespace App\Http\Controllers\Web;

use Laravel\Lumen\Routing\Controller as BaseController;

class LandingController extends BaseController
{
    
    public function managerHome()
    {
        return view('website.manager');
    }

    public function managerSignUp()
    {
        return view('website.msignup');
    }
}
