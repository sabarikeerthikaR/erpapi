<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PaymentOptions extends Model
{

     protected $fillable = [
        'account','business_no','descripton'
    ];
    protected $table = 'payment_options';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\payment_options');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
