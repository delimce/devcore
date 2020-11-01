<?php

namespace App\Repositories;

use App\Models\Manager\Support;
use Carbon\Carbon as Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SupportRepository 
{

    public function saveSupportRequest(array $data)
    {
        $created = Support::create($data);
        return $created;   
    }

}