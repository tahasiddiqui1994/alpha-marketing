<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name', 'email'
  ];
}
