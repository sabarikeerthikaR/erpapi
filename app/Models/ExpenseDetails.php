<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseDetails extends Model
{

     protected $fillable = [
        'date','title','category', 'amount', 'person_responsible','receipt','description','created_by'
    ];
    protected $table = 'expensedetails';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\expensedetails');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
