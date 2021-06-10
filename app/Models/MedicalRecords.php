<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MedicalRecords extends Model
{

     protected $fillable = [
        'date','student', 'sickness', 'notify_parent','action_taken','comment','action_taken_by'
    ];
    protected $table = 'medical_records';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\medical_records');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
