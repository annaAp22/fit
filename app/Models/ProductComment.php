<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Date;

class ProductComment extends Model
{
    protected $table = 'product_comments';

    protected $fillable = [
        'product_id',
        'name',
        'email',
        'text',
        'rating',
        'status',
        'pros',
        'cons'
    ];



    public function scopePublished($query) {
        return $query->where('status', 1);
    }



    public function product() {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }



    public function dateLocale() {
        return (new Date($this->date))->format('d F Y');
    }

    public function datePicker() {
        return (new Date($this->date))->format('d.m.Y');
    }

}
