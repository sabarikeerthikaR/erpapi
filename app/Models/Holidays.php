<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Holidays extends Model
{

     protected $fillable = [
       'date','holiday'
    ];
    protected $table = 'holiday';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\holiday');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}

