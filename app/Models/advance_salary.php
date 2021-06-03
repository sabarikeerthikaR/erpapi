<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Advance_salary extends Model
{
  
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
