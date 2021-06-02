<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class EmployeeAttendance extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'date','employee','time_in', 'time_out'
    ];
    protected $table = 'employee_attendance';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\employee_attendance');
    }
}
