<?php

namespace App\Services;

use App\Models\Localization\Municipality;
use App\Models\Localization\Province;
use App\Models\Localization\State;

class LocalizationService
{

    
    /**
     * getStates
     *
     * @param  mixed $countryId
     * @return void
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
     * @return void
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
     * @return void
     */
    public function getMunicipalities($provinceId)
    {
        $municipalities = Municipality::whereStatus(1);
        if ($provinceId) {
            return $municipalities->whereProvinceId($provinceId)->get();
        }
        return $municipalities->get();
    }
}
