<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Suspend extends Model
{
   
      
     protected $fillable = [
       'date','reason','admission_id','created_by'
    ];
    protected $table = 'add_suspended';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\add_suspended');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
