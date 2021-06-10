<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Book_for_fund extends Model
{

     protected $fillable = [
       'category', 'pages','author','edition' ,'description','title','purchase_date','quantity','receipt','date'
    ];
    protected $table = 'book_for_fund';
    protected $primaryKey = 'book_for_fund_id';

    public function categories()
    {
        return $this->belongsToMany('App\book_for_fund');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
