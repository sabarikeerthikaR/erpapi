<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Std_class extends Model
{
   
     protected $fillable = [
        'name','description','status','stream','teacher','exam_recording'
    ];
    protected $table = 'std_class';
    protected $primaryKey = 'class_id';

    public function categories()
    {
        return $this->belongsToMany('App\std_class');
    }
}
