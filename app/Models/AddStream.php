<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class AddStream extends Model
{
    use HasFactory;
    use Loggable; 
    protected $fillable = [
       'class','stream','status','teacher','exam_recording','date'
   ];
   protected $table = 'add_stream';
   protected $primaryKey = 'id';

   public function categories()
   {
       return $this->belongsToMany('App\add_stream');
   }
   public function comments()
   {
       return $this->hasMany('App\class_stream');
   }
}
