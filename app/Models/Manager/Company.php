<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use hasFactory;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'company';

    /**
     * The attributes that are mass assignable. & use insert method
     * @var array
     */
    protected $fillable = [
        'name', 'nif', 'phone', 'manager_id'
    ];

    public function manager()
    {
        return $this->belongsTo(Manager::class, 'manager_id');
    }
}
