<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsParam extends Model
{
    protected $fillable = ['name', 'value'];

    // Scopes
    public function scopeReservation($query)
    {
        return $query->where('name', 'reserv');
    }
}
