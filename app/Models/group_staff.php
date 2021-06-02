<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Group_staff extends Model
{
    use HasFactory;
    use Loggable; 
    protected $fillable = [
        'name'
    ];
    protected $table = 'group_staff';
    protected $primaryKey = 'employee_type';

    public function categories()
    {
        return $this->belongsToMany('App\group_staff');
    }
}
