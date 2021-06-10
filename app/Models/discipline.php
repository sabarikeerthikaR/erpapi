<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Discipline extends Model
{
      
    protected $fillable = [
        'date','culprit','reported_by','others','notify_parent','description','action_taken','comment','created_by','action_taken_on'
    ];
    protected $table = 'discipline';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\discipline');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
