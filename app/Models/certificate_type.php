<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Certificate_type extends Model
{
   use HasFactory;
   use Loggable; 
     protected $fillable = [
       'name',
    ];
    protected $table = 'certificate_type';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\certificate_type');
    }
}
