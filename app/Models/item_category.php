<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Item_category extends Model
{

     protected $fillable = [
        'name','description','created_by',
    ];
    protected $table = 'item_category';
    protected $primaryKey = 'item_category_id';

    public function categories()
    {
        return $this->belongsToMany('App\item_category');
    }
}
