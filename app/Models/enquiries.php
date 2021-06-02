<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Enquiries extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'date','first_name','last_name', 'dob', 'gender','phone','email', 'class','about_us','description','status'
    ];
    protected $table = 'enquiries';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\enquiries');
    }
}
