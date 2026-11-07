<?php

namespace Sugihart\DeviceManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Device extends Model
{
    protected $connection = 'sdm';

    protected $table = 'device';

    protected $guarded = ['id'];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
