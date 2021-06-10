<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Staf_bulk_clearance extends Model
{
    
     protected $fillable = [
        'staff_id','department_id','date', 'clear', 'charge','comment','staff_id_2'
    ];
    protected $table = 'staf_bulk_clearance';
    protected $primaryKey = 'staf_clearance_id';

    public function categories()
    {
        return $this->belongsToMany('App\staf_bulk_clearance');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
