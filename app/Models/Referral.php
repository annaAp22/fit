<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    //
    protected $fillable = [
        'name',
        'partner_id',
        'order_id',
        'phone',
        'email',
    ];
}
