<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class RecordSales extends Model
{
     
     protected $fillable = [
        'date','student', 'item', 'quantity','unit_price','total','transaction_no','pay_method'
    ];
    protected $table = 'record_sales';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\record_sales');
    }
}
