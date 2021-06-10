<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiries extends Model
{

     protected $fillable = [
        'date','first_name','last_name', 'dob', 'gender','phone','email', 'class','about_us','description','status'
    ];
    protected $table = 'enquiries';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\enquiries');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
