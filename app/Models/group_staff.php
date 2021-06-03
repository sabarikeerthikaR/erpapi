<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Group_staff extends Model
{

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
