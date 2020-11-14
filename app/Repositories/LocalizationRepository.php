<?php

namespace App\Repositories;

use App\Models\Localization\Municipality;
use App\Models\Localization\Province;
use App\Models\Localization\State;

class LocalizationRepository
{


    /**
     * getStates
     *
     * @param  mixed $countryId
     * @return Collection
     */
    public function getStates($countryId)
    {
        $states = State::whereStatus(1);
        if ($countryId) {
            return $states->whereCountryId($countryId)->get();
        }
        return $states->get();
    }


    /**
     * getProvinces
     *
     * @param  mixed $stateId
     * @return Collection
     */
    public function getProvinces($stateId)
    {
        $provinces = Province::whereStatus(1);
        if ($stateId) {
            return $provinces->whereStateId($stateId)->get();
        }
        return $provinces->get();
    }



    /**
     * getMunicipalities
     *
     * @param  mixed $provinceId
     * @return Collection
     */
    public function getMunicipalities($provinceId)
    {
        $municipalities = Municipality::whereStatus(1);
        if ($provinceId) {
            return $municipalities->whereProvinceId($provinceId)->get();
        }
        return $municipalities->get();
    }


    /**
     * getCitiesByCountry
     *
     * @param  int $countryId
     * @return Collection
     */
    public function getCitiesByCountry($countryId)
    {
        $cities = Province::join('local_state', 'local_state.id', '=', 'local_province.state_id')
            ->select("local_province.id","local_province.name")
            ->where("local_state.country_id", $countryId)
            ->where("local_province.status", 1)->get();
        return $cities;
    }
}
