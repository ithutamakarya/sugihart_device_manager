<?php

namespace Sugihart\DeviceManager\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $connection = 'sdm';

    protected $table = 'vendor';

    protected $guarded = ['id'];
}
