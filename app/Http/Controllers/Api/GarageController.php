<?php

namespace App\Http\Controllers\Api;

use App\Models\Manager\Manager;
use App\Services\GarageService;
use Illuminate\Http\Request;

use Validator;

class GarageController extends ApiController
{
    protected $garage;
    public function __construct(GarageService $garage)
    {
        $this->garage = $garage;
    }

    
    public function getNetworks(){
        return $this->okResponse($this->garage->getNetworks());
    }

}