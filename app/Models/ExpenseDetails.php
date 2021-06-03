<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseDetails extends Model
{

     protected $fillable = [
        'date','title','category', 'amount', 'person_responsible','receipt','description'
    ];
    protected $table = 'expensedetails';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\expensedetails');
    }
}
