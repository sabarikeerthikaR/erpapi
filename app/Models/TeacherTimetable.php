<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class TeacherTimetable extends Model
{
   
     protected $fillable = [
        'start_time','end_time','day','subject','staff','class'
    ];
    protected $table = 'teacher_timetable';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\teacher_timetable');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
