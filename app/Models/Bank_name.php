<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Bank_name extends Model
{
   use HasFactory;
   use Loggable;  
     protected $fillable = [
        'name'
    ];
    protected $table = 'bank_name';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\add_item');
    }
}
