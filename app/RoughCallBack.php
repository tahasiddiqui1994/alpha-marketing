<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoughCallBack extends Model
{
    public $table = "roughcallbacks";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message_id', 'date_time','type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'remember_token',
    ];

}
