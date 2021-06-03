<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee_structure extends Model
{

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
