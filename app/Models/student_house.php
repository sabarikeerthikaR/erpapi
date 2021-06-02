<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Student_house extends Model
{
   
     protected $fillable = [
        'name','slogan','leader','description'
    ];
    protected $table = 'student_house';
    protected $primaryKey = 'house_id';

    public function categories()
    {
        return $this->belongsToMany('App\student_house');
    }
}
