<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Fee_extras extends Model
{

     protected $fillable = [
        'title','fee_type','amount','charged','description'
    ];
    protected $table = 'fee_extras';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\fee_extras');
    }
}
