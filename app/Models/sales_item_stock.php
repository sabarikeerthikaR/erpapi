<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class  Sales_item_stock extends Model
{
   
     protected $fillable = [
        'purchase_date','item','quantity','unit_price','buying_price','person_responsible','receipt','description'
    ];
    protected $table = 'sales_item_stock';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\sales_item_stock');
    }
}
