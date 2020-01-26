<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'tbl_manager';

    /**
     * The attributes that are mass assignable. & use insert method
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [
        'password',
    ];
}
