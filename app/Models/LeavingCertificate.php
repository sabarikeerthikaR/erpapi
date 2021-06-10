<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class LeavingCertificate extends Model
{
     protected $fillable = [
        'date','student','headteachr_remark', 'curricular_activity','created_by','created_on'
    ];
    protected $table = 'leaving_certificate';
    protected $primaryKey ='id';

    public function categories()
    {
        return $this->belongsToMany('App\leaving_certificate');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
