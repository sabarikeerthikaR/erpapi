<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Certificate_base extends Model
{

     protected $fillable = [
       'name','student','certificate_type','serial_number','mean_grade','points','certificate',
    ];
    protected $table = 'certificate_base';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\certificate_base');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
