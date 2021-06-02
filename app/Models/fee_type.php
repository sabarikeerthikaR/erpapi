<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Fee_type extends Model
{
   use HasFactory;
   use Loggable;  
     protected $fillable = [
        'name'
    ];
    protected $table = 'fee_type';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\fee_type');
    }
}
