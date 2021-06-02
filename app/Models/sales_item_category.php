<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Sales_item_category extends Model
{
  
     protected $fillable = [
        'name','description'
    ];
    protected $table = 'sales_item_category';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\sales_item_category');
    }
}
