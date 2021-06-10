<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostelBed extends Model
{

     protected $fillable = [
        'hostel_room','bed_no','status'
    ];
    protected $table = 'hostel_bed';
    protected $primaryKey ='id';

    public function categories()
    {
        return $this->belongsToMany('App\hostel_bed');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
