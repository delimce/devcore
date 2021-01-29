<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'user';

    protected $appends = ['fullname'];

    public function getFullnameAttribute()
    {
        return $this->getAttribute('name') . ' ' . $this->getAttribute('lastname');
    }

    /**
     * The attributes that are mass assignable. & use insert method
     * @var array
     */
    protected $fillable = [
        'name', 'lastname', 'email', 'password', 'active'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [
        'password'
    ];


    /**
     * @return string
     */
    public function fullName()
    {
        return $this->name . ' ' . $this->lastname;
    }
}
