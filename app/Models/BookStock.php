<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class BookStock extends Model
{

     protected $fillable = [
       'date', 'qty' ,'book'  ];
    protected $table = 'book_stock';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\book_stock');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
