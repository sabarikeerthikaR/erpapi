<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ExamMark extends Model
{

     protected $fillable = [
        'exam','subject','student','mark_one','mark_two','total_mark','grading_system','class','convert_percentage'
    ];
    protected $table = 'exam_mark';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\exam_mark');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
