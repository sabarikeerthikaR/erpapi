<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class StudentClass extends Model
{
   
     protected $fillable = [
        'student','class'
    ];
    protected $table = 'student_class';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\student_class');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
