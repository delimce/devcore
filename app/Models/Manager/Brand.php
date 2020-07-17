<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'brand';

         /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];


}
