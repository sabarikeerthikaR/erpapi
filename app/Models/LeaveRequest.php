<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class LeaveRequest extends Model
{
    
     protected $fillable = [
        'from_date','to_date','reason','accept', 'request_by', 'request_to','date'
    ];
    protected $table = 'leave_request';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\leave_request');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
