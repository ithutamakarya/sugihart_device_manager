<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'log_users';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    

}