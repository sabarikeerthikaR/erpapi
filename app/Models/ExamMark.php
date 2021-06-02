<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class ExamMark extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'exam','subject','student','mark_one','mark_two','total_mark','grading_system'
    ];
    protected $table = 'exam_mark';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\exam_mark');
    }
}
