<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Books_category extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
       'name', 'description',   ];
    protected $table = 'books_category';
    protected $primaryKey = 'book_category_id';

    public function categories()
    {
        return $this->belongsToMany('App\books_category');
    }
}
