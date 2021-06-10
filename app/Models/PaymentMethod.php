<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{

    protected $fillable = [
        'name'
    ];
    protected $table = 'payment_method';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\payment_method');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
