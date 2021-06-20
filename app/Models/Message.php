<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Message extends Model
{

     protected $fillable = [
        'sender','receiver', 'message', 'replay'
    ];
    protected $table = 'message';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\message');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
