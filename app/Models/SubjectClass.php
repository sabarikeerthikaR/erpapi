<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class SubjectClass extends Model
{
    
    protected $fillable = [
       'subject','class','term'
   ];
   protected $table = 'subject_class';
   protected $primaryKey = 'id';

   public function categories()
   {
       return $this->belongsToMany('App\subject_class');
   }
    
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
