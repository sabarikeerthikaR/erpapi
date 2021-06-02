<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Other_event extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
       'title','date','start_time','end_time','venue','description',
    ];
    protected $table = 'other_event';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\other_event');
    }
}
