<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Terms extends Model
{
    
     protected $fillable = [
       'name' ,'from_year','to_year'
    ];
    protected $table = 'terms';
    protected $primaryKey = 'term_id';

    public function categories()
    {
        return $this->belongsToMany('App\terms');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
