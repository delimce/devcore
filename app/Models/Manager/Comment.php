<?php

namespace App\Models\Manager;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'garage_comment';

    public function garage()
    {
        return $this->belongsTo(Garage::class, 'garage_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
