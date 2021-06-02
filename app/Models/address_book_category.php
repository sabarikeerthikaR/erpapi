<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Address_book_category extends Model
{
    use HasFactory;
    use Loggable;  
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
