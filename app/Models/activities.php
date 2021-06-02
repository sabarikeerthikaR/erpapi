<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
   
      
     protected $fillable = [
        'action_performer','action_to','action_title','description','read_status'
    ];
    protected $table = 'activities';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\activities');
    }
}
