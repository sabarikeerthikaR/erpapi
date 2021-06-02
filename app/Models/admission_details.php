<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Admission_details extends Model
{
     use HasFactory;
     use Loggable; 
     protected $fillable = [
        'date','class_id','admission_no', 'house_id'
    ];
    protected $table = 'admission_details';
    protected $primaryKey = 'ad_detail_id';

    public function categories()
    {
        return $this->belongsToMany('App\admission_details');
    }
}
