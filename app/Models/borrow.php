<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Borrow extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'actual_return', 'book_id', 'admission_id','borrow_date','remarks','status','return_date'   ];
    protected $table = 'borrow';
    protected $primaryKey = 'b_id';

    public function categories()
    {
        return $this->belongsToMany('App\borrow');
    }
}
