<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Add_event extends Model
{
     use HasFactory;
     use Loggable; 
     protected $fillable = [
       'title','start_date','end_date','venue','visibility','description',
    ];
    protected $table = 'add_event';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\add_event');
    }
}
