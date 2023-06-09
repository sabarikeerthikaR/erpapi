<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Sales_item extends Model
{
   
     protected $fillable = [
        'item_name','category','description','unit_price'
    ];
    protected $table = 'sales_item';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\sales_item');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
