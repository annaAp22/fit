<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    //
  protected $fillable = [
      'sysname',
      'name',
      'text',
      'text',
      'lat',
      'long',
      'status'
  ];

}
