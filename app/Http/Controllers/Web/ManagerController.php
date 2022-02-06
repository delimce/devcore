<?php

namespace App\Http\Controllers\Web;

use App\Repositories\GarageRepository;
use App\Repositories\ManagerRepository;
use Laravel\Lumen\Routing\Controller as BaseController;

class ManagerController extends BaseController
{
    public function __construct(private ManagerRepository $manager, private GarageRepository $garage)
    {
    }

    public function index()
    {
        return view('manager.app');
    }
}
