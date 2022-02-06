<?php

namespace App\Http\Controllers\Api;

use App\Repositories\LocalizationRepository;

use Illuminate\Http\JsonResponse;

class LocalizationController extends ApiController
{
    public function __construct(private LocalizationRepository $local)
    {
    }

    /**
     * @param bool $countryId
     * @return JsonResponse
     */
    public function getStates($countryId = false)
    {
        return $this->okResponse($this->local->getStates($countryId));
    }

    /**
     * @param bool $stateId
     * @return JsonResponse
     */
    public function getProvinces($stateId = false)
    {
        return $this->okResponse($this->local->getProvinces($stateId));
    }


    /**
     * @param bool $provinceId
     * @return JsonResponse
     */
    public function getMunicipalities($provinceId = false)
    {
        return $this->okResponse($this->local->getMunicipalities($provinceId));
    }

    /**
     * @param bool $countryId
     * @return JsonResponse
     */
    public function getCitiesByCountryId($countryId)
    {
        return $this->okResponse($this->local->getCitiesByCountry($countryId));
    }
}
