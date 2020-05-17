<?php

namespace App\Services;

use App\Models\Manager\Garage;
use App\Models\Manager\Network;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class GarageService
{

    /**
     * @return mixed
     */
    public function getNetworks()
    {
        $networks = Network::whereStatus(1)->get();
        return $networks;
    }


    /**
     * @param integer $managerId
     * @return mixed
     */
    public function getGarageByManagerId($managerId)
    {
        return Garage::whereManagerId($managerId)->first();
    }


    /**
     * save garage information
     * @param array $garage
     * @return bool
     */
    public function saveGarage(array $garage)
    {
        try {
            $result = Garage::firstOrNew(
                [
                    'manager_id' => $garage['manager'],
                ]
            );
            $result->name = $garage['name'];
            $result->phone = $garage['phone'];
            $result->address = $garage['address'];
            $result->network_id = (!empty($garage['network_id'])) ? $garage['network_id'] : null;
            $result->address2 = (isset($garage['address2'])) ? $garage['address2'] : "";
            $result->desc = (isset($garage['desc'])) ? $garage['desc'] : "";
            $result->country_id = $garage['country_id'];
            $result->state_id = $garage['state_id'];
            $result->province_id = $garage['province_id'];
            $result->zipcode = $garage['zipcode'];
            $result->save();
            return true;
        } catch (QueryException $ex) {
            Log::error($ex);
            return false;
        }
    }
}
