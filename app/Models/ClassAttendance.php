<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassAttendance extends Model
{

    protected $fillable = [
        'date','attendance_for','student','present','remark','class','taken_on','taken_by'
    ];
    protected $table = 'class_attendance';	
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\class_attendance');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
