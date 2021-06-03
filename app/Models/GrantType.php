<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class GrantType extends Model
{

    protected $fillable = [
        'name'
    ];
    protected $table = 'grant_type';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\grant_type');
    }
}
