<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsOrder extends Model
{
    protected $fillable = [
        'ms_description',
        'ms_agent_id',
        'ms_positions',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
