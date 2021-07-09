<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Fee_payment extends Model
{
     
     protected $fillable = [
        'created_by','student','date','amount', 'payment_method', 'transaction_no',
        'bank','tuition_fee','term','description','class'
    ];
    protected $table = 'fee_payment';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\fee_payment');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
