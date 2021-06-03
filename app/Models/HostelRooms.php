<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostelRooms extends Model
{
 
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
