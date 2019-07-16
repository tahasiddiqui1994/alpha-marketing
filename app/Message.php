<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
  protected $fillable = [
      'text','closer','customername','status','fees','allowcallback','merchantID','note','updatedBy'
  ];
}
