<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Fee_waivers extends Model
{

     protected $fillable = [
        'date','student','amount','term','year','remarks'
    ];
    protected $table = 'fee_waivers';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\fee_waivers');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
