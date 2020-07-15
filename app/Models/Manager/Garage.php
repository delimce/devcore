<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;

class Garage extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'garage';

    protected $fillable = [
         'manager_id'
    ];


    public function network()
    {
        return $this->belongsTo('App\Models\Manager\Network', 'network_id');
    }

    public function services()
    {
        return $this->hasMany('App\Models\Manager\GarageService','garage_id');
    }

}
