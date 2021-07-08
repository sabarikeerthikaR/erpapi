<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Petty_cash extends Model
{
  
     protected $fillable = [
        'petty_date','description','ammount', 'person_responsible','created_by'
    ];
    protected $table = 'petty_cash';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\petty_cash');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
