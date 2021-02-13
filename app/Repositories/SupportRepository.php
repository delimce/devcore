<?php

namespace App\Repositories;

use App\Models\Manager\Support;

class SupportRepository 
{

    public function saveSupportRequest(array $data)
    {
        return Support::create($data);   
    }

}