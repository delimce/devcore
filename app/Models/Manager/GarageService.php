<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Model;

class GarageService extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'garage_service';

    /**
     * The attributes that are mass assignable. & use insert method
     * @var array
     */
    protected $fillable = [
        'garage_id', 'service_id', 'segment', 'type', 'category', 'brand_id', 'price'
    ];


    protected $casts = [
        'price' => 'double',
    ];


    public function garage()
    {
        return $this->belongsTo('App\Models\Manager\Garage', 'garage_id');
    }

    public function service()
    {
        return $this->belongsTo('App\Models\Manager\Service', 'service_id');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Manager\Brand', 'brand_id');
    }
}
