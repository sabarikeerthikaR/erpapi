<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Bank_name extends Model
{
  
     protected $fillable = [
        'name'
    ];
    protected $table = 'bank_name';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\add_item');
    }
}
