<?php

namespace App\Http\Controllers\Web;

use Laravel\Lumen\Routing\Controller as BaseController;

class LandingController extends BaseController
{
    
    public function home()
    {
        return view('website.index');
    }
}
