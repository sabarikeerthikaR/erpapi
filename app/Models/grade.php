<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Grade extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
       'title','remarks',
    ];
    protected $table = 'grade';
    protected $primaryKey = 'gradings_id';

    public function categories()
    {
        return $this->belongsToMany('App\grade');
    }
}
