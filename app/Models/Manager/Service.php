<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'service';

     /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];


}
