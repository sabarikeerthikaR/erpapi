<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee_charge extends Model
{

     protected $fillable = [
        'name',
    ];
    protected $table = 'fee_charge';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\fee_charge');
    }
}
