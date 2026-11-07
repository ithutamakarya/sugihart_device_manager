<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $connection = 'sdm';

    protected $table = 'vendor';

    protected $guarded = [];
}
