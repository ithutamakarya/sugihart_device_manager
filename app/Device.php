<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'device';
    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }
    
}