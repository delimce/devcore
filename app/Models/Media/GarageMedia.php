<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;

class GarageMedia extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'garage_media';

    /**
     * The attributes that are mass assignable. & use insert method
     * @var array
     */
    protected $fillable = [
        'garage_id', 'original', 'extension', 'mime', 'size', 'path'
    ];
}
