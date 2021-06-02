<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Fee_waivers extends Model
{
     use HasFactory;
     use Loggable; 
     protected $fillable = [
        'date','student','amount','term','year','remarks'
    ];
    protected $table = 'fee_waivers';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\fee_waivers');
    }
}
