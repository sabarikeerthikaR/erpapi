<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{

     protected $fillable = [
       'title','remarks',
    ];
    protected $table = 'grade';
    protected $primaryKey = 'gradings_id';

    public function categories()
    {
        return $this->belongsToMany('App\grade');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
