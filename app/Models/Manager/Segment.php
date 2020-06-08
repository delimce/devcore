<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    protected $table = 'tbl_segment';


    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

}