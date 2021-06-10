<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission_details extends Model
{
     protected $fillable = [
        'date','class_id','admission_no', 'house_id'
    ];
    protected $table = 'admission_details';
    protected $primaryKey = 'ad_detail_id';

    public function categories()
    {
        return $this->belongsToMany('App\admission_details');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
