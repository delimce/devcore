<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'support_request';

    protected $fillable = [
        'garage_id', 'manager_id', 'description', 'request_type'
    ];

     /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];


}
