<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Discipline extends Model
{
     use HasFactory;
     use Loggable; 
    protected $fillable = [
        'date','culprit','reported_by','others','notify_parent','description','action_taken','comment','created_by','action_taken_on'
    ];
    protected $table = 'discipline';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\discipline');
    }
}
