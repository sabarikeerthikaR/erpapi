<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class AssignTeacher extends Model
{
    use HasFactory;
    use Loggable; 
    protected $fillable = [
      'class','teacher','method' 
   ];
   protected $table = 'assign_teacher';
   protected $primaryKey = 'id';

   public function categories()
   {
       return $this->belongsToMany('App\assign_teacher');
   }
}
