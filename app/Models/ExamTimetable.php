<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class ExamTimetable extends Model
{
    
     protected $fillable = [
        'exam','class','subject','total_mark', 'minimum_mark', 'date','start_time','end_time'
    ];
    protected $table = 'exam_timetable';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\exam_timetable');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
