<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class AssignBed extends Model
{
     use HasFactory;
     use Loggable; 
     protected $fillable = [
        'date','student','term','year','bed','comment','assigned_by'
    ];
    protected $table = 'assign_bed';
    protected $primaryKey ='id';

    public function categories()
    {
        return $this->belongsToMany('App\assign_bed');
    }
}
