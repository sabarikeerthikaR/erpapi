<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Year extends Model
{
     
     protected $fillable = [
        'year',
    ];
    protected $table = 'year';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\year');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
