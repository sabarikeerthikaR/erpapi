<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ClassRooms extends Model
{
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
