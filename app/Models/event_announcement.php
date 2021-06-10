<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event_announcement extends Model
{

     protected $fillable = [
       'title','description',
    ];
    protected $table = 'event_announcement';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\event_announcement');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
