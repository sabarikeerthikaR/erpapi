<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Emergency_contact extends Model
{
   use HasFactory;
   use Loggable;  
     protected $fillable = [
        'admission_id','first_name_e','relation_e', 'phone_e', 'email_e','id_no_e','address_e', 'info_provided_by','middle_name_e','last_name_e'
    ];
    protected $table = 'emergency_contact';
    protected $primaryKey = 'emergency_id';

    public function categories()
    {
        return $this->belongsToMany('App\emergency_contact');
    }
}
