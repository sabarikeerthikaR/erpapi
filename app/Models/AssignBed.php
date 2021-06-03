<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AssignBed extends Model
{
 
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
