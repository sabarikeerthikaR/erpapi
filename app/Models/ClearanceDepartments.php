<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class ClearanceDepartments extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'name','description'
    ];
    protected $table = 'department';
    protected $primaryKey = 'department_id';

    public function categories()
    {
        return $this->belongsToMany('App\department');
    }
}
