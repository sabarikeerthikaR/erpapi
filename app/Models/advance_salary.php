<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Advance_salary extends Model
{
   use HasFactory;
   use Loggable; 
     protected $fillable = [
        'date','employee','amount', 'comment'
    ];
    protected $table = 'advance_salary';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\advance_salary');
    }
}
