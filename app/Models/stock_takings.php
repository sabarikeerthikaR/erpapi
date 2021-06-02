<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Stock_takings extends Model
{
   
     protected $fillable = [
        'product_name','closing_stock', 'taken_on', 'taken_by'
    ];
    protected $table = 'stock_takings';
    protected $primaryKey = 'stock_taking_id';

    public function categories()
    {
        return $this->belongsToMany('App\stock_takings');
    }
}
