<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Syllabus extends Model
{
    
    protected $fillable = [
       'date','class','subject','description','file'
   ];
   protected $table = 'syllabus';
   protected $primaryKey = 'id';

   public function categories()
   {
       return $this->belongsToMany('App\syllabus');
   }
    
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
