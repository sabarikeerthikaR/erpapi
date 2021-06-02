<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class ClassRooms extends Model
{
   use HasFactory;
   use Loggable; 
     protected $fillable = [
        'name','capacity','description'
    ];
    protected $table = 'class_room';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\class_room');
    }
}
