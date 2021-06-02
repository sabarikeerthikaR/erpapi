<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class AddExcActivities extends Model
{
    use HasFactory;
    use Loggable; 
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
