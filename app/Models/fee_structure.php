<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Fee_structure extends Model
{
   use HasFactory;
   use Loggable;  
     protected $fillable = [
        'term','class','fee_amount'
    ];
    protected $table = 'fee_structure';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\fee_structure');
    }
}
