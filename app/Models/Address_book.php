<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Address_book extends Model
{

     protected $fillable = [
        'name',
    ];
    protected $table = 'address_book';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\address_book');
    }
}
