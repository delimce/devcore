<?php

namespace App\Services;

use App\Models\Manager\Network;

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
}
