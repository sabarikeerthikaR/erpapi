<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class StaffClearance extends Model
{
   
     protected $fillable = [
        'staff','department','date', 'clear', 'charge','comment','confirmed_by'
    ];
    protected $table = 'staff_clearance';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\staff_clearance');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
