<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Add_emp_sal extends Model
{
    use HasFactory;
    use Loggable;  
     protected $fillable = [
        'employee','salary_method','basic_salary', 'NHIF_Amount','NSSF_Amount','deductions','allowances','staff_with_student_deduction','bank_name','acc_no','NHIF_Number','NSSF_Number'
    ];
    protected $table = 'add_emp_sal';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\add_emp_sal');
    }
}
