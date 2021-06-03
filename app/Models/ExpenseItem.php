<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
 
     protected $fillable = [
        'name','description'
    ];
    protected $table = 'expense_item';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\expense_item');
    }
}
