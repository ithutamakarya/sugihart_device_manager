<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $connection = 'sdm';

    protected $table = 'device';

    protected $guarded = [];
}
