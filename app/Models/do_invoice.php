<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Do_invoice extends Model
{

     protected $fillable = [
        'term'
    ];
    protected $table = 'do_invoice';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\do_invoice');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
