<?php

namespace App\Models\Localization;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'tbl_local_province';

    public function state()
    {
        return $this->belongsTo('App\Models\Localization\State', 'state_id');
    }

    public function municipalities()
    {
        return $this->hasMany('App\Models\Localization\Municipality','province_id');
    }

}
