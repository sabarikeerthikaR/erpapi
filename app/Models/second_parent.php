<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Second_parent extends Model
{
   
     protected $fillable = [
        'admission_id','title_s','relation_s','first_name_s', 'last_name_s','phone_s','email_s','id_passport_s','occupation_s','address_s','postal_code_s','passport_photo_s','national_id_s','middle_name_s',
    ];
    protected $table = 'second_parent';
    protected $primaryKey = 'parent2_id';

    public function categories()
    {
        return $this->belongsToMany('App\second_parent');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
