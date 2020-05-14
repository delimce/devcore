<?php

namespace App\Http\Controllers\Api;

use App\Services\LocalizationService;

use Validator;

class LocalizationController extends ApiController
{
    protected $local;
    public function __construct(LocalizationService $local)
    {
        $this->local = $local;
    }

    public function getStates($countryId = false)
    {
        return $this->okResponse($this->local->getStates($countryId));
    }

    public function getProvinces($stateId = false)
    {
        return $this->okResponse($this->local->getProvinces($stateId));
    }


    public function getMunicipalities($provinceId = false)
    {
        return $this->okResponse($this->local->getMunicipalities($provinceId));
    }
}
