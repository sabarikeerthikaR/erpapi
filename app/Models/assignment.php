<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Assignment extends Model
{
  
     protected $fillable = [
       'title','start_date','end_date','class','upload_document','assignment','comment','created_on','created_by'
    ];
    protected $table = 'assignment';
    protected $primaryKey = 'assignment_id';

    public function categories()
    {
        return $this->belongsToMany('App\assignment');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
