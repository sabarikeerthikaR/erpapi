<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AssignTeacher extends Model
{

    protected $fillable = [
      'class','teacher','method' 
   ];
   protected $table = 'assign_teacher';
   protected $primaryKey = 'id';

   public function categories()
   {
       return $this->belongsToMany('App\assign_teacher');
   } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
