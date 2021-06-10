<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
 
     protected $fillable = [
        'date','employee','time_in', 'time_out'
    ];
    protected $table = 'employee_attendance';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\employee_attendance');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
