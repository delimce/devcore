<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;

class ManagerAccess extends Model
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'manager_access_log';

    /**
     * The attributes that are mass assignable. & use insert method
     * @var array
     */
    protected $fillable = [
        'ip', 'manager_id', 'agent'
    ];


    public function manager()
    {
        return $this->belongsTo('App\Models\Manager\Manager', 'manager_id');
    }

}
