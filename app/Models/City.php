<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class City extends Model
{
  protected $fillable = [
    'title',
    'sysname',
    'long',
    'lat',
    'zoom',
    'status',
  ];

  //relationships
  public function shops() {
    return $this->hasMany('App\Models\Shop')->where('status', 1);
  }
  //scopes
  public function scopePublished($query) { $query->where('status', 1); }
  //mutators
  public function setSysnameAttribute($value)
  {
    if(!$value)
      $value = $this->attributes['title'];
    $this->attributes['sysname'] = Str::slug($value);
  }
  public function setZoomAttribute($value)
  {
    $this->attributes['zoom'] = $value?$value:15;
  }

}
