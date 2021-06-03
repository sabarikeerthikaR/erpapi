<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee_arrears extends Model
{
 
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
