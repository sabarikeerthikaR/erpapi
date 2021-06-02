<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Class_stream extends Model
{
   use HasFactory;
   use Loggable; 
     protected $fillable = [
        'name'
    ];
    protected $table = 'class_stream';
    protected $primaryKey = 'stream_id';

    public function categories()
    {
        return $this->belongsToMany('App\class_stream');
    }
}
