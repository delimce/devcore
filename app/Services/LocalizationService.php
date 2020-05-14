<?php

namespace App\Services;

use App\Models\Localization\Municipality;
use App\Models\Localization\Province;
use App\Models\Localization\State;

class LocalizationService
{


    public function getStates($countryId)
    {
        $states = State::whereStatus(1);
        if ($countryId) {
            return $states->whereCountryId($countryId)->get();
        }
        return $states->get();
    }


    public function getProvinces($stateId)
    {
        $provinces = Province::whereStatus(1);
        if ($stateId) {
            return $provinces->whereStateId($stateId)->get();
        }
        return $provinces->get();
    }


    public function getMunicipalities($provinceId)
    {
        $municipalities = Municipality::whereStatus(1);
        if ($provinceId) {
            return $municipalities->whereProvinceId($provinceId)->get();
        }
        return $municipalities->get();
    }
}
