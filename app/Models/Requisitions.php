<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Requisitions extends Model
{  
    protected $fillable = [
       'item','qty','unit_price','sub_total','total','created_by','date','data'
   ];
   protected $table = 'requisitions';
   protected $primaryKey = 'id';

   public function categories()
   {
       return $this->belongsToMany('App\requisitions');
   }
}
