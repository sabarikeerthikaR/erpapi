<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class StudentTimetable extends Model
{
   
     protected $fillable = [
        'start_time','end_time','day','subject','class'
    ];
    protected $table = 'student_timetable';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\student_timetable');
    }
}
