<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    protected $connection = 'sdm';

    protected $table = 'log_activity';

    protected $guarded = [];
}
