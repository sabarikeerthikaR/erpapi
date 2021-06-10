<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Laravel\Passport\HasApiTokens;
class OnlineRegistration extends Model
{
     use HasApiTokens;
     protected $fillable = [
        'date','first_name', 'middle_name', 'last_name','admission_for','dob', 'gender','religion','nationality', 'first_parent','first_parent_occupation','relation_f','email_f','phone_f','address_f','second_parent','second_parent_occupation','relation_s','email_s','phone_s','address_s','former_school','reason_for_leaving','grade_completed','disability_if_any','local_guardian','comments','image','address','phone','status'
    ];
    protected $table = 'online_registration';
    protected $primaryKey = 'online_reg_id';
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
