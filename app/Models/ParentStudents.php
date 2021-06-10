<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentStudents extends Model
{
   
      
     protected $fillable = [
        'p_id','admission_id'
    ];
    protected $table = 'parent_students';
    protected $primaryKey = 'id';

     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
