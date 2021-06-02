<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Laravel\Passport\HasApiTokens;

class Settings extends Model
{
    use HasApiTokens;
    
     protected $fillable = [
        'group_name','key_name','key_value',
    ];
    protected $table = 'setings';
    protected $primaryKey = 's_d';

    public function categories()
    {
        return $this->belongsToMany('App\setings');
    }
}
