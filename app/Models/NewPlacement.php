<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class NewPlacement extends Model
{

     protected $fillable = [
        'student','date','position', 'rep_of','date_upto','description'
    ];
    protected $table = 'new_placement';
    protected $primaryKey ='id';

    public function categories()
    {
        return $this->belongsToMany('App\new_placement');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
