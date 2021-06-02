<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Event_announcement extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
       'title','description',
    ];
    protected $table = 'event_announcement';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\event_announcement');
    }
}
