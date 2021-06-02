<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Item_stock extends Model
{
    
     protected $fillable = [
        'added_by','date','item_name','quantity', 'unit_price', 'total','person_responsible','receipt',
    ];
    protected $table = 'item_stock';
    protected $primaryKey = 'item_stock_id';

    public function categories()
    {
        return $this->belongsToMany('App\item_stock');
    }
}
