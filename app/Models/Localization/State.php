<?php

namespace App\Models\Localization;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'tbl_local_state';

    public function country()
    {
        return $this->belongsTo('App\Models\Localization\Country', 'country_id');
    }

    public function provinces()
    {
        return $this->hasMany('App\Models\Localization\Province','state_id');
    }

}
