<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AddActivities extends Model
{

     protected $fillable = [
        'name'
    ];
    protected $table = 'add_activities';
    protected $primaryKey ='id';

    public function categories()
    {
        return $this->belongsToMany('App\add_activities');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
