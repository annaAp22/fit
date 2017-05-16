<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVar extends Model
{
    protected $table = 'page_vars';

    protected $fillable = ['page_id', 'var', 'value'];

    public static $rules = ['var' => 'sysname'];

    public function page() {
        return $this->belongsTo('App\Models\Page');
    }

}
