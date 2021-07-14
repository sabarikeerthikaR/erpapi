<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Online_payment extends Model
{
 
     protected $fillable = [
        'name','email','phone','address','card_number','transaction_id'
    ];
    protected $table = 'online_payment';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\online_payment');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
