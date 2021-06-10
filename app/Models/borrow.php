<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Borrow extends Model
{

     protected $fillable = [
        'actual_return', 'book_id', 'admission_id','borrow_date','remarks','status','return_date'   ];
    protected $table = 'borrow';
    protected $primaryKey = 'b_id';

    public function categories()
    {
        return $this->belongsToMany('App\borrow');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
