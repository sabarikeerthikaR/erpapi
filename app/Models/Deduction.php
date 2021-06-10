<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Deduction extends Model
{

     protected $fillable = [
        'name','amount'
    ];
    protected $table = 'deduction';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\deduction');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
