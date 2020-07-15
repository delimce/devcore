<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'service_type';

        /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

}
