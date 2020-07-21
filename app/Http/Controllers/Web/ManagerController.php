<?php

namespace App\Http\Controllers\Web;

use App\Repositories\GarageRepository;
use App\Repositories\ManagerRepository;
use Laravel\Lumen\Routing\Controller as BaseController;

class ManagerController extends BaseController
{
    protected $manager;
    protected $garage;

    public function __construct(ManagerRepository $manager, GarageRepository $garage)
    {
        $this->manager = $manager;
        $this->garage = $garage;
    }

    public function index()
    {
        return view('manager.app');
    }
}
