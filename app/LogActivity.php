<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    protected $table = 'log_activity';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    

}