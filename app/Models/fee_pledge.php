<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee_pledge extends Model
{

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
