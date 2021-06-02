<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Fee_arrears extends Model
{
   use HasFactory;
   use Loggable;  
     protected $fillable = [
        'student','amount','term','year',
    ];
    protected $table = 'fee_arrears';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\fee_arrears');
    }
}
