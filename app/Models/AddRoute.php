<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddRoute extends Model
{
 
     protected $fillable = [
        'name'
    ];
    protected $table = 'add_route';
    protected $primaryKey ='id';

    public function categories()
    {
        return $this->belongsToMany('App\add_route');
    }
}
