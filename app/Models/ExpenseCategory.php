<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class ExpenseCategory extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'name','description'
    ];
    protected $table = 'expense_category';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\expense_category');
    }
}
