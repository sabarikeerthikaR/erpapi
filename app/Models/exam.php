<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Exam extends Model
{
     protected $fillable = [
       'titte','term','year','weight','start_date' ,'end_date',
    ];
    protected $table = 'exam';
    protected $primaryKey = 'exam_id';

    public function categories()
    {
        return $this->belongsToMany('App\exam');
    }
}
