<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'manager';

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
        'name', 'lastname', 'email', 'password', 'phone', 'token'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [
        'password', 'token',
    ];


    /**
     * @return string
     */
    public function fullName()
    {
        return $this->name . ' ' . $this->lastname;
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'manager_id');
    }


    public function supportRequests()
    {
        return $this->hasMany(Support::class, 'manager_id');
    }


    public function access()
    {
        return $this->hasMany(ManagerAccess::class, 'manager_id');
    }
}
