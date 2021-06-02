<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Studnt_certificate extends Model
{
    
     protected $fillable = [
       'student_name','date','title','certificate_no','upload_certificate' 
    ];
    protected $table = 'studnt_certificate';
    protected $primaryKey = 'std_certificate';

    public function categories()
    {
        return $this->belongsToMany('App\studnt_certificate');
    }
}
