<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Position extends Model
{
    
     protected $fillable = [
        'name','description'
    ];
    protected $table = 'position';
    protected $primaryKey ='id';

    public function categories()
    {
        return $this->belongsToMany('App\position');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
