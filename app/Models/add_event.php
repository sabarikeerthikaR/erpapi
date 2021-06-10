<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Add_event extends Model
{

     protected $fillable = [
       'title','start_date','end_date','venue','visibility','description',
    ];
    protected $table = 'add_event';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\add_event');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
