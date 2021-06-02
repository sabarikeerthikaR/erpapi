<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class ClassAttendance extends Model
{
    use HasFactory;
    use Loggable; 
    protected $fillable = [
        'date','attendance_for','student','present','remark','class','taken_on','taken_by'
    ];
    protected $table = 'class_attendance';	
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\class_attendance');
    }
}
