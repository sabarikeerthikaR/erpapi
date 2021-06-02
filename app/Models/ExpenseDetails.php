<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class ExpenseDetails extends Model
{
    use HasFactory;
    use Loggable; 
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
