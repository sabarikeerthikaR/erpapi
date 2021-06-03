<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AddExcActivities extends Model
{

     protected $fillable = [

        'activity','year','stud_name','class',
        'adm_no','status'
    ];
    protected $table = 'extra_curricular_activity';
    protected $primaryKey ='id';

    public function categories()
    {
        return $this->belongsToMany('App\extra_curricular_activity');
    }
}
