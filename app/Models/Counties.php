<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Counties extends Model
{

     protected $fillable = [
        'name','description'
    ];
    protected $table = 'counties';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\counties');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
