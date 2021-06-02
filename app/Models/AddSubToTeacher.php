<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class AddSubToTeacher extends Model
{
    use HasFactory;
    use Loggable; 
    protected $fillable = [
       'subject','teacher','class'
   ];
   protected $table = 'add_sub_to_teacher';
   protected $primaryKey = 'id';

   public function categories()
   {
       return $this->belongsToMany('App\add_sub_to_teacher');
   }
}
