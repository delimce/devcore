<?php

namespace App\Models\Manager;

use App\Models\Media\GarageMedia;
use App\Models\Localization\State;
use App\Models\Localization\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Garage extends Model
{
    use HasFactory;

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

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'garage_id');
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

    public function comments()
    {
        return $this->hasMany(Comment::class, 'garage_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }
}
