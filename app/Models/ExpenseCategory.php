<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ExpenseCategory extends Model
{

     protected $fillable = [
        'name','description'
    ];
    protected $table = 'expense_category';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\expense_category');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
