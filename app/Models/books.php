<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Books extends Model
{

     protected $fillable = [
       'title', 'author','publisher','year_published','isbn_number','book_category_id','edition','pages','copyright' ,'shelf','memo',
    'purchase_date' ,'quantity'  ];
    protected $table = 'books';
    protected $primaryKey = 'book_id';

    public function categories()
    {
        return $this->belongsToMany('App\books');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
