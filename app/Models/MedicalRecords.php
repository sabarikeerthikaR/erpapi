<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class MedicalRecords extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'date','student', 'sickness', 'notify_parent','action_taken','comment','action_taken_by'
    ];
    protected $table = 'medical_records';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\medical_records');
    }
}
