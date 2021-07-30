<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Device extends Model
{

     protected $fillable = [
        'user_id','device_id','device'
    ];
    protected $table = 'device_log';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\device_log');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
