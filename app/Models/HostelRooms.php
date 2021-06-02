<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class HostelRooms extends Model
{
    use HasFactory;
    use Loggable;   
     protected $fillable = [
        'hostel','room_name','description'
    ];
    protected $table = 'hostel_rooms';
    protected $primaryKey ='id';

    public function categories()
    {
        return $this->belongsToMany('App\hostel_rooms');
    }
}
