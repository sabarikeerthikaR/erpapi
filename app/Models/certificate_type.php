<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Certificate_type extends Model
{

     protected $fillable = [
       'name',
    ];
    protected $table = 'certificate_type';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\certificate_type');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
