<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Add_item extends Model
{

     protected $fillable = [
        'name','category_id','reorder_level', 'description', 'created_by'
    ];
    protected $table = 'add_item';
    protected $primaryKey = 'item_id';

    public function categories()
    {
        return $this->belongsToMany('App\add_item');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
