<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Other_event extends Model
{
 
     protected $fillable = [
       'title','date','start_time','end_time','venue','description',
    ];
    protected $table = 'other_event';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\other_event');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
