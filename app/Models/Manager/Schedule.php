<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'tbl_garage_schedule';

    /**
     * The attributes that are mass assignable. & use insert method
     * @var array
     */
    protected $fillable = [
        'garage_id', 'day', 'am1', 'am2', 'pm1', 'pm2'
    ];


    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
