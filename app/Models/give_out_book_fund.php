<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Give_out_book_fund extends Model
{

     protected $fillable = [
       'borrow_date', 'student','book','remark','status','actual_date'
    ];
    protected $table = 'give_out_book_fund';
    protected $primaryKey = 'give_out_id';

    public function categories()
    {
        return $this->belongsToMany('App\give_out_book_fund');
    }
}
