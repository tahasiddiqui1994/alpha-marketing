<?php

namespace App;

use Illuminate\Database\Eloquent\Model ;
use App\User ;

class sessionEye extends Model
{
    protected $table = "sessioneye" ;

    public function users() {
        return $this->belongsToOne(User::class) ;
    }

    protected $fillable = [
        'userID', 'userName','startTime','endTime',
    ] ;

    protected $hidden = [
        'id',
   ] ;

   public $timestamps = false ;
}
