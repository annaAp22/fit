<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'sysname',

        'name',
        'content'
    ];

    // Info pages
    public function scopeInfo($query)
    {
        return $query->with('vars')
                        ->whereHas('vars', function($query) {
                            $query->where('var', 'type')
                                    ->where('value', 'info');
                        });
    }

    // Info pages
    public function scopeHelp($query)
    {
        return $query->with('vars')
            ->whereHas('vars', function($query) {
                $query->where('var', 'type')
                    ->where('value', 'help');
            });
    }


    public function vars() {
        return $this->hasMany('App\Models\PageVar', 'page_id');
    }

    public function getVar($var) {
        $page_var = $this->vars()->where('var', $var)->first();
        return ($page_var ? $page_var->value : null);
    }

    public function photos() {
        return $this->hasMany('App\Models\PagePhoto', 'page_id');
    }
}
