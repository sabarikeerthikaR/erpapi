<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Months extends Model
{
   
      
     protected $fillable = [
     'name'
    ];
    protected $table = 'month';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\month');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
