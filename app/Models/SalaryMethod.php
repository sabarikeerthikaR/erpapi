<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class SalaryMethod extends Model
{
  
    protected $fillable = [
        'name'
    ];
    protected $table = 'salary_method';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\salary_method');
    }
}
