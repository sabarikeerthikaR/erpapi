<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\school;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class First_parent extends Model
{
   use HasFactory;
   use Loggable; 
     protected $fillable = [
        'admission_id','title','relation','first_name', 'last_name','phone','email','id_passport','occupation','address','postal_code','passport_photo','national_id','middle_name','status'
    ];
    protected $table ='first_parent';
    protected $primaryKey ='parent1_id';
      protected $foreignKey ='admission_id';

    public function categories()
    {
        return $this->belongsToMany('App\first_parent');
    }
}
