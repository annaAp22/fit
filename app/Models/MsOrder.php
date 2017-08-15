<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsOrder extends Model
{
    protected $fillable = [
        'ms_description',
        'ms_agent_id',
        'ms_positions',
        'error',
        'error_message',
    ];

    protected $casts = [
        'error' => 'boolean',
        'error_message' => 'array',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // Scopes
    public function scopeNoErrors($query)
    {
        return $query->where('error', 0);
    }
}
