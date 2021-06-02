<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Fee_pledge extends Model
{
   use HasFactory;
   use Loggable; 
     protected $fillable = [
        'student','pledge_date','amount','status','remark',
    ];
    protected $table = 'fee_pledge';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\fee_pledge');
    }
}
