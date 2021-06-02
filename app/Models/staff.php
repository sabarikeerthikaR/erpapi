<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\school;


class Staff extends Model
{
 
     protected $fillable = [
        'first_name','employee_type','middle_name', 'last_name', 'gender','DOB','religion', 'marital_status','id_number','pin_number', 'status','tsc_employee','tsc_number','knut_member', 'knut_number','kuppet_member','kuppet_number','date_employed', 'contract_id','department','position','qualification', 'proposed_leaving_date','subjects_specialized','phone','email', 'address','employment_histroy','full_name','phone_1','email_1','address_1','additional','passport_photo', 'credential_certificate','national_id_copy','tsc_letter', 'disable'
    ];
    protected $table = 'staff';
    protected $primaryKey = 'employee_id';

    public function categories()
    {
        return $this->belongsToMany('App\staff');
    }
}
