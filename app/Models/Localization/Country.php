<?php

namespace App\Models\Localization;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'tbl_local_country';

    public function states()
    {
        return $this->hasMany('App\Models\Localization\State','country_id');
    }

}
