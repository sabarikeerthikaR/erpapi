<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Certificate_base extends Model
{
   use HasFactory;
   use Loggable;  
     protected $fillable = [
       'name','student','certificate_type','serial_number','mean_grade','points','certificate',
    ];
    protected $table = 'certificate_base';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\certificate_base');
    }
}
