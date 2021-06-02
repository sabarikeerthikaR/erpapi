<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Grading_system extends Model
{
   use HasFactory;
   use Loggable; 
     protected $fillable = [
       'title','pass_mark','description','created_by','created_on'
    ];
    protected $table = 'grading_system';
    protected $primaryKey = 'grading_systm_id';

    public function categories()
    {
        return $this->belongsToMany('App\grading_system');
    }
}
