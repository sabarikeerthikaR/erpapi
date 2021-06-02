<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Certificate_subject extends Model
{
   use HasFactory;
   use Loggable;  
     protected $fillable = [
       'name','base_id','subject','grade',
    ];
    protected $table = 'certificate_subject';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\certificate_subject');
    }
}
