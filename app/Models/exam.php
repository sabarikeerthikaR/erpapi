<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Exam extends Model
{
    use HasFactory;
    use Loggable; 
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
