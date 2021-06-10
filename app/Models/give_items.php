<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Give_items extends Model
{

     protected $fillable = [
        'date','item','quantity', 'given_to', 'comment'
    ];
    protected $table = 'give_items';
    protected $primaryKey = 'give_item_id';

    public function categories()
    {
        return $this->belongsToMany('App\give_items');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
