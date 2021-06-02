<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class BookStock extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
       'date', 'qty' ,'book'  ];
    protected $table = 'book_stock';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\book_stock');
    }
}
