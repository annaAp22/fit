<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerTransfer extends Model
{
    //
    protected $fillable = [
        'partner_id',
        'manager_id',
        'status',
        'money',
    ];
    public function operator() {
        return $this->belongsTo('App\User', 'manager_id');
    }
    public function partner() {
        return $this->belongsTo('App\Models\Partner');
    }

}
