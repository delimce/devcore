<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'network';

    protected $hidden = [
        'created_at', 'updated_at',
    ];


}
