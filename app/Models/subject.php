<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Subject extends Model
{
     protected $fillable = [
       'term','name','code','short_name' ,'priority','type','sub_units','class','subject_id','unit_title','mark','subject_id'
    ];
    protected $table = 'subjects';
    protected $primaryKey = 'subject_id';

    public function categories()
    {
        return $this->belongsToMany('App\subjects');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
