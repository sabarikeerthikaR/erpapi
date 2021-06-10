<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class RegistrationDetails extends Model
{
   
     protected $fillable = [
        'reg_no','reg_date','inst_category', 'inst_cluster', 'county','sub_county','ward','inst_type','edu_systm','edu_level','knec_code','inst_accommodation','scholars_gender','locality','kra_pin'
    ];
    protected $table = 'registration_details';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\registration_details');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
