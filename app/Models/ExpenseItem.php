<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
 
     protected $fillable = [
        'name'
    ];
    protected $table = 'expense_item';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\expense_item');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
