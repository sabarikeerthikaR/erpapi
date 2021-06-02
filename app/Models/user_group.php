<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class User_group extends Model
{
   
     protected $fillable = [
        'name','description'
    ];
    protected $table = 'user_group';
    protected $primaryKey = 'group_id';

    public function categories()
    {
        return $this->belongsToMany('App\user_group');
    }
}
