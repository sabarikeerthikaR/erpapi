<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class SubUnit extends Model
{
    
    protected $fillable = [
       'name','mark','subject'
   ];
   protected $table = 'sub_unit';
   protected $primaryKey = 'id';

   public function categories()
   {
       return $this->belongsToMany('App\sub_unit');
   }
    
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
