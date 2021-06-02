<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class GrantType extends Model
{
     use HasFactory;
     use Loggable; 
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
