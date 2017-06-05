<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
  protected $fillable = [
    'title',
    'city_id',
    'address',
    'phone',
    'email',
    'link',
    'long',
    'lat',
    'status',
  ];
  //scopes
  public function scopePublished($query) { $query->where('status', 1); }
  //other
  public function links()
  {
    return explode(',', $this->attributes['link']);
  }
}
