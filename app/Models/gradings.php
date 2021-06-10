<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Gradings extends Model
{

     protected $fillable = [
       'grade','min_mark','max_mark','remark','grading_system_id','created_by','created_on'
    ];
    protected $table = 'gradings';
    protected $primaryKey = 'grading_id';

    public function categories()
    {
        return $this->belongsToMany('App\gradings');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
