<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Books_category extends Model
{

     protected $fillable = [
       'name', 'description',   ];
    protected $table = 'books_category';
    protected $primaryKey = 'book_category_id';

    public function categories()
    {
        return $this->belongsToMany('App\books_category');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
