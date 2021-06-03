<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Address_book_category extends Model
{
 
     protected $fillable = [
        'name','description'
    ];
    protected $table = 'address_book_category';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\address_book_category');
    }
}
