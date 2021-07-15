<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Exam extends Model
{
     protected $fillable = [
       'title','term','year','weight','start_date' ,'end_date','description'
    ];
    protected $table = 'exam';
    protected $primaryKey = 'exam_id';

    public function categories()
    {
        return $this->belongsToMany('App\exam');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
