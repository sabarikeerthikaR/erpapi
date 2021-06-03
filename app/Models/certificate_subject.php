<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Certificate_subject extends Model
{
  
     protected $fillable = [
       'name','base_id','subject','grade',
    ];
    protected $table = 'certificate_subject';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\certificate_subject');
    }
}
