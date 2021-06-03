<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Class_stream extends Model
{
 
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
