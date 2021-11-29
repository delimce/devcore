<?php

namespace App\Models\Localization;

use App\Services\Commons\StringsHandlerService;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'local_province';
    protected $appends = ['url'];

    public function state()
    {
        return $this->belongsTo('App\Models\Localization\State', 'state_id');
    }

    public function municipalities()
    {
        return $this->hasMany('App\Models\Localization\Municipality','province_id');
    }

    public function getUrlAttribute()
    {
        return StringsHandlerService::slugify($this->name);
    }

}
