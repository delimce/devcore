<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'tbl_company';

    /**
     * The attributes that are mass assignable. & use insert method
     * @var array
     */
    protected $fillable = [
        'name', 'nif', 'phone', 'manager_id'
    ];

    public function manager()
    {
        return $this->belongsTo('App\Models\Manager\Manager', 'manager_id');
    }
}
