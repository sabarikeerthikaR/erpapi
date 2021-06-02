<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Department extends Model
{
    use HasFactory;
    use Loggable; 
    protected $fillable = [
        'name','description',
    ];
    protected $table = 'staff_department';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\staff_department');
    }
}
