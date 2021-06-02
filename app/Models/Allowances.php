<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Allowances extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'name','amount'
    ];
    protected $table = 'allowances';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\allowances');
    }
}
