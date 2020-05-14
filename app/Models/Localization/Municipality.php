<?php

namespace App\Models\Localization;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'tbl_local_municipality';

    public function province()
    {
        return $this->belongsTo('App\Models\Localization\Province', 'province_id');
    }

}
