<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    protected $table = 'user_groups';

    protected $fillable = ['name', 'descr'];

    public function users()
    {
        return $this->hasMany('App\User', 'group_id');
    }

}
