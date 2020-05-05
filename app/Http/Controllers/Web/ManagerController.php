<?php

namespace App\Http\Controllers\Web;

use App\Services\GarageService;
use App\Services\ManagerService;
use Laravel\Lumen\Routing\Controller as BaseController;

class ManagerController extends BaseController
{
    protected $manager;
    protected $garage;

    public function __construct(ManagerService $manager, GarageService $garage)
    {
        $this->manager = $manager;
        $this->garage = $garage;
    }

    public function index()
    {
        return view('manager.app');
    }
}
