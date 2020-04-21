<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
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
        'name', 'lastname', 'email', 'password', 'phone', 'token'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    /**
     * @return string
     */
    public function fullName()
    {
        return $this->name . ' ' . $this->lastname;
    }
}
