<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\Admission as Authenticatable;
use Laravel\Passport\HasApiTokens;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\school;


class Admission extends Model
{
      use Notifiable;
     
     protected $fillable = [

          'year',
          'admission_id','boarding','first_name', 'middle_name', 'last_name','dob','gender', 'student_status','disabled','blood_group', 'religion','allergies','former_Scl','entry_mark', 'doctor_name','doctor_phone','preferred_hospital','scholarship', 'type','sponsor_detail','phone','location','contact_person', 'citizenship','home_country','sub_country','residence', 'emergency_phone','student_phone','email','image', 'birth_certificate','admission_no','class','house','reason','suspend','completion_yr',
          'admitted_by','new'
    ];
    protected $table = 'admission';
    protected $primaryKey = 'admission_id';

    public function categories()
    {
        return $this->belongsToMany('App\admission');
    }
}
