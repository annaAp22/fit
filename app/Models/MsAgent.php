<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsAgent extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'ms_uuid',
        'ms_name',
        'ms_tag',
        'ms_phone',
        'ms_email',
    ];
}
