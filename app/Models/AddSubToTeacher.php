<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddSubToTeacher extends Model
{

    protected $fillable = [
       'subject','teacher','class'
   ];
   protected $table = 'add_sub_to_teacher';
   protected $primaryKey = 'id';

   public function categories()
   {
       return $this->belongsToMany('App\add_sub_to_teacher');
   } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
