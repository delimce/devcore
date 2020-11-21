<?php

namespace App\Models\Manager;

use App\Models\Media\GarageMedia;
use Illuminate\Database\Eloquent\Model;

class Garage extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'garage';

    protected $fillable = [
        'manager_id'
    ];


    public function media()
    {
        return $this->hasMany(GarageMedia::class, 'garage_id');
    }

    public function network()
    {
        return $this->belongsTo(Network::class, 'network_id');
    }

    public function services()
    {
        return $this->hasMany(GarageService::class, 'garage_id');
    }

    public function supportRequests()
    {
        return $this->hasMany(Support::class, 'garage_id');
    }
}
