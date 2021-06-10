<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee_type extends Model
{ 
     protected $fillable = [
        'name'
    ];
    protected $table = 'fee_type';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\fee_type');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
