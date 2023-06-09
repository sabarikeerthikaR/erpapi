<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class StudentTimetable extends Model
{
   
     protected $fillable = [
        'start_time','end_time','class','subject','day'
    ];
    protected $table = 'student_timetable';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\student_timetable');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
