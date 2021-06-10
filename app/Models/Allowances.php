<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Allowances extends Model
{

     protected $fillable = [
        'name','amount'
    ];
    protected $table = 'allowances';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\allowances');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
