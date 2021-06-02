<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Sms extends Model
{
      
     protected $fillable = [
        'message','receiver','sender','read_status'
    ];
    protected $table = 'sms';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\sms');
    }
}
