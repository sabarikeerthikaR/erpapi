<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Transport extends Model
{
   
     protected $fillable = [

        'route','year','stud_name','class',
        'adm_no'
    ];
    protected $table = 'transport';
    protected $primaryKey ='id';

    public function categories()
    {
        return $this->belongsToMany('App\transport');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
