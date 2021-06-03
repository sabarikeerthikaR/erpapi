<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Department extends Model
{
 
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
