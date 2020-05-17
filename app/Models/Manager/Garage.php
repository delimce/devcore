<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;

class Garage extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'tbl_garage';

    protected $fillable = [
         'manager_id'
    ];


    public function network()
    {
        return $this->belongsTo('App\Models\Manager\Network', 'network_id');
    }

}
