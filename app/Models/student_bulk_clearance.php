<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Student_bulk_clearance extends Model
{
     
     protected $fillable = [
        'admission_id','student_card_returned','department_id', 'date', 'clear','charge','comment', 'staff_id','created_by'
    ];
    protected $table = 'student_bulk_clearance';
    protected $primaryKey = 'stu_clearance_id';

    public function categories()
    {
        return $this->belongsToMany('App\student_bulk_clearance');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
