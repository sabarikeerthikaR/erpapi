<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class StudentClearance extends Model
{
  
     protected $fillable = [
        'student','date','department', 'cleard', 'charge','confirmed_by','pending_items'
    ];
    protected $table = 'student_clearance';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\student_clearance');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
