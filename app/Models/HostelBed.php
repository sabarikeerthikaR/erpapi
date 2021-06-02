<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class HostelBed extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'hostel_room','bed_no','status'
    ];
    protected $table = 'hostel_bed';
    protected $primaryKey ='id';

    public function categories()
    {
        return $this->belongsToMany('App\hostel_bed');
    }
}
